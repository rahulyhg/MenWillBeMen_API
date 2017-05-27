<?php

require_once '../include/DbHandler.php';
require_once '../include/PassHash.php';
require_once '../include/Responce.php';
require '.././libs/Slim/Slim.php';

require_once '../include/Push.php';
require_once '../include/firebase.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
 
// User id from db - Global Variable
$user_id = NULL;

/**
 * Adding Middle Layer to authe   nticate every request
 * Checking if the request has valid api key in the 'Authorization' header
 */
 
  function apache_request_headers() {
  $arh = array();
  $rx_http = '/\AHTTP_/';
  foreach($_SERVER as $key => $val) {
    if( preg_match($rx_http, $key) ) {
      $arh_key = preg_replace($rx_http, '', $key);
      $rx_matches = array();
      // do some nasty string manipulations to restore the original letter case
      // this should work in most cases
      $rx_matches = explode('_', $arh_key);
      if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
        foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
        $arh_key = implode('-', $rx_matches);
      }
      $arh[$arh_key] = $val;
    }
  }
  return( $arh );
}

function authenticate(\Slim\Route $route) {
    // Getting request headers
	
	//print_r( $_SERVER);
    $headers = apache_request_headers();

	//print_r($headers);
    $response = array();
    $app = \Slim\Slim::getInstance();

    // Verifying Authorization Header
    if (isset($headers['AUTHORIZATION'])) {
        $db = new DbHandler();

        // get the api key
        $api_key = $headers['AUTHORIZATION'];
        // validating api key
        if (!$db->isValidApiKey($api_key)) {
            // api key is not present in users table
            $response["error"] = true;
            $response["message"] = "Access Denied. Invalid Api key";
            echoRespnse(401, $response);
            $app->stop();
        } else {
            global $user_id;
            // get user primary key id
            $user_id = $db->getUserId($api_key);
        }
    } else {
        // api key is missing in header
        $response["error"] = true;
        $response["message"] = "Api key is misssing";
        echoRespnse(400, $response);
        $app->stop();
    }
}
/*
function authenticate(\Slim\Route $route) {
    // Getting request headers
    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();

    // Verifying Authorization Header
    if (isset($headers['Authorization'])) {
        $db = new DbHandler();

        // get the api key
        $api_key = $headers['Authorization'];
        // validating api key
        if (!$db->isValidApiKey($api_key)) {
            // api key is not present in users table
            $response["error"] = true;
            $response["message"] = "Access Denied. Invalid Api key";
            echoRespnse(401, $response);
            $app->stop();
        } else {
            global $user_id;
            // get user primary key id
            $user_id = $db->getUserId($api_key);
        }
    } else {
        // api key is missing in header
        $response["error"] = true;
        $response["message"] = "Api key is misssing";
        echoRespnse(400, $response);
        $app->stop();
    }
}
*/

/**
 * ----------- METHODS WITHOUT AUTHENTICATION ---------------------------------
 */
/**
 * User Registration
 * url - /register
 * method - POST
 * params - name, email, password
 */
$app->post('/register', function() use ($app) {
            // check for required params
            verifyRequiredParams(array(
//                'name',
//                'email'
//                , 'password'
//                ,'device_id'
                'firebase_reg_id'

                ));

            $Responce = new Responce();

            // reading post params
            $fname = $app->request->post('fname');
            $lname = $app->request->post('lname');
            $phone = $app->request->post('phone');
            $email = $app->request->post('email');
            $device_id = $app->request->post('device_id');
            $firebase_reg_id = $app->request->post('firebase_reg_id');

            // validating email address
//            validateEmail($email);

            $db = new DbHandler();
            $res = $db->createUser($fname, $lname, $phone, $email, $device_id, $firebase_reg_id);

            if ($res == USER_CREATED_SUCCESSFULLY) {
                
                
                $user = $db->getUserByDeviceId($device_id);

                $Responce->setError(false);
                $Responce->setMessage("You are successfully registered");
                $Responce->setData("user",$user);

            } else if ($res == USER_CREATE_FAILED) {
                $Responce->setError(true);
                $Responce->setMessage("Oops! An error occurred while registereing");

            } else if ($res == USER_UPDATED) {

                $user = $db->getUserByDeviceId($device_id);
                $Responce->setError(false);
                $Responce->setMessage("User updated");
                $Responce->setData("user",$user);


            }
            // echo json response
            echoRespnse(201, $Responce->setArray());
        });

/**
 * User Login
 * url - /login
 * method - POST
 * params - email, password
 */
$app->post('/login', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('email', 'password'));

            // reading post params
            $email = $app->request()->post('email');
            $password = $app->request()->post('password');
            $response = array();

            $db = new DbHandler();
            // check for correct email and password
            if ($db->checkLogin($email, $password)) {
                // get the user by email
                $user = $db->getUserByEmail($email);

                if ($user != NULL) {
                    $response["error"] = false;
                    $response['name'] = $user['name'];
                    $response['email'] = $user['email'];
                    $response['apiKey'] = $user['api_key'];
                    $response['createdAt'] = $user['created_at'];
                } else {
                    // unknown error occurred
                    $response['error'] = true;
                    $response['message'] = "An error occurred. Please try again";
                }
            } else {
                // user credentials are wrong
                $response['error'] = true;
                $response['message'] = 'Login failed. Incorrect credentials';
            }

            echoRespnse(200, $response);
        });

/*
 * ------------------------ METHODS WITH AUTHENTICATION ------------------------
 */

/**
 * Listing all tasks of particual user
 * method GET
 * url /tasks          
 */
$app->post('/update_whatsapp_count', function() use ($app) {
            // check for required params

            verifyRequiredParams(array('post_id'));
            $post_id = $app->request()->post('post_id');
			
            global $user_id;
            
            $response = array();
            $db = new DbHandler();
            
            $Responce = new Responce();

            // fetch task
            $result = $db->updateWhatsappCount($post_id);

            if ($result) {
                $Responce->setError(false);
                $Responce->setMessage("Whatsapp count Updated");

            } else {
                $Responce->setError(false);
                $Responce->setMessage("Filed");
      
            }
            
            echoRespnse(201, $Responce->setArray());
        });
        
$app->post('/update_share_count', function() use ($app) {
            // check for required params

            verifyRequiredParams(array('post_id'));
            $post_id = $app->request()->post('post_id');
			
            global $user_id;
            
            $response = array();
            $db = new DbHandler();
            
            $Responce = new Responce();

            // fetch task
            $result = $db->updateShareCount($post_id);

            if ($result) {
                $Responce->setError(false);
                $Responce->setMessage("Share count Updated");

            } else {
                $Responce->setError(false);
                $Responce->setMessage("Filed");
      
            }
            
            echoRespnse(201, $Responce->setArray());
        });
	
        
$app->post('/like_post',  'authenticate',  function() use ($app) {
            // check for required params

            verifyRequiredParams(array('post_id'));
            $post_id = $app->request()->post('post_id');
			
            global $user_id;
            
            $response = array();
            $db = new DbHandler();
            
            $Responce = new Responce();

            // fetch task
            $result = $db->likePost($user_id, $post_id);

            if ($result) {
                $Responce->setError(false);
                $Responce->setMessage("Liked");

            } else {
                $Responce->setError(false);
                $Responce->setMessage("Filed");
      
            }
            
            echoRespnse(201, $Responce->setArray());
        });
      
$app->post('/unlike_post',  'authenticate',  function() use ($app) {
            // check for required params

            verifyRequiredParams(array('post_id'));
            $post_id = $app->request()->post('post_id');
			
            global $user_id;
            
            $response = array();
            $db = new DbHandler();
            
            $Responce = new Responce();

            // fetch task
            $result = $db->unLikePost($user_id, $post_id);

            if ($result) {
                $Responce->setError(false);
                $Responce->setMessage("Unliked");

            } else {
                $Responce->setError(false);
                $Responce->setMessage("Filed");
      
            }
            
            echoRespnse(201, $Responce->setArray());
        });
        
$app->post('/add_post',  'authenticate', function() use ($app) {
            // check for required params

            verifyRequiredParams(array('language_id','category_id','post'));
           
            $language_id = $app->request()->post('language_id');
            $category_id = $app->request()->post('category_id');
            $post = $app->request()->post('post');
			
            global $user_id;
            
            $response = array();
            $db = new DbHandler();
            
            $Responce = new Responce();

            // fetch task
            $result = $db->insertPost($user_id,$language_id,$category_id,$post);

            if ($result) {
                $Responce->setError(false);
                $Responce->setMessage("Your message is submitted.");

            } else {
                $Responce->setError(true);
                $Responce->setMessage("failed");
      
            }
            
            echoRespnse(201, $Responce->setArray());
        });
 
$app->post('/submit_feedback',  'authenticate', function() use ($app) {
            // check for required params

            verifyRequiredParams(array('emoji','feedback'));
           
            $emoji = $app->request()->post('emoji');
            $feedback = $app->request()->post('feedback');
			
            global $user_id;
            
            $response = array();
            $db = new DbHandler();
            
            $Responce = new Responce();

            // fetch task
            $result = $db->insertFeedback($user_id,$feedback,$emoji);

            if ($result) {
                $Responce->setError(false);
                $Responce->setMessage("Your feedback is submitted.");

            } else {
                $Responce->setError(true);
                $Responce->setMessage("failed");
      
            }
            
            echoRespnse(201, $Responce->setArray());
        });
        
        
$app->post('/send_notification',  'authenticate',  function() use ($app) {
            // check for required params

            
            $title = $app->request()->post('title');
            $message = $app->request()->post('message');
            $push_type = $app->request()->post('push_type');
            $image = $app->request()->post('image');
	    $firebase_reg_id = $app->request()->post('firebase_reg_id');

            global $user_id;
            
//            $response = array();
//            $db = new DbHandler();
//            
            $Responce = new Responce();
            
            
            $firebase = new Firebase();
            $push = new Push();
 
            // optional payload
            $payload = array();
            $payload['team'] = 'India';
            $payload['score'] = '5.6';
 
                           
            // whether to include to image or not
            $include_image = isset($image) ? TRUE : FALSE;
 
 
            $push->setTitle($title);
            $push->setMessage($message);
            if ($include_image) {
                $push->setImage($image);
            } else {
                $push->setImage('');
            }
            $push->setIsBackground(FALSE);
            $push->setPayload($payload);
 
 
            $json = '';
            $response = '';
 
            if ($push_type == 'topic') {
                $json = $push->getPush();
                $response = $firebase->sendToTopic('global', $json);
                
                $Responce->setError(false);
                $Responce->setMessage("Sent");
                $Responce->setData("json",$json);

                $Responce->setData("responce",$response);
            } else if ($push_type == 'individual') {
                $json = $push->getPush();
                $response = $firebase->send($firebase_reg_id, $json);
        }
        
        
//
//            // fetch task
//            $result = $db->unLikePost($user_id, $post_id);
//
//            if ($result) {
//                $Responce->setError(false);
//                $Responce->setMessage("Unliked");
//
//            } else {
//                $Responce->setError(false);
//                $Responce->setMessage("Filed");
//      
//            }
//            
            echoRespnse(201, $Responce->setArray());
        });

        
//$app->get('/get_posts/:tag_id/:auther_id','authenticate',
//		
//		function($tag_id,$auther_id,$quote_id) 
//		{
//            global $user_id;
//            
//            
//            $response = array();
//            $db = new DbHandler();
//            
//              $Responce = new Responce();
//
//            // fetch task
//            $result = $db->getQuotes($tag_id,$auther_id,$quote_id,null,null,null);
//
//            if ($result != NULL) {
//                $Responce->setError(false);
//                $Responce->setMessage("false");
//                $Responce->setData('author_quotes',$result);
//
////                $result["error"] = false;
////                $response["id"] = $result["id"];
////                $response["task"] = $result["task"];
////                $response["status"] = $result["status"];
////                $response["createdAt"] = $result["created_at"];
////                echoRespnse(200, $Responce);
//            } else {
//                $Responce->setError(false);
//                $Responce->setMessage("false");
//               // $response["error"] = true;
//               // $response["message"] = "The requested resource doesn't exists";
////                echoRespnse(404, $response);
//            }
//            
//            echoRespnse(201, $Responce->setArray());
//        });

$app->get('/get_posts/:language_id/:category_id/:type/:page_no', 'authenticate', 
        function($language_id,$category_id,$type,$page_no) {

    global $user_id;
	
	if($category_id == "null"){
		$category_id = NULL;
	}


    $response = array();
    $db = new DbHandler();

    $Responce = new Responce();

    // fetch task
    if($type == LATEST){
            $resultLatest = $db->getPosts($user_id, $category_id,$language_id, $type,$page_no,POST_LIMIT);
    }else{
            $resultTop = $db->getPosts($user_id, $category_id,$language_id,$type,$page_no,POST_LIMIT);
    }


    if ($type != NULL) 
	{
        $Responce->setError(false);
        $Responce->setMessage("false");
        
        if($type == LATEST){
        $Responce->setData('latest', $resultLatest);
        }else{
        $Responce->setData('top', $resultTop);
        }
    
    
//        $Responce->setData('top', $resultTop);

//                $result["error"] = false;
//                $response["id"] = $result["id"];
//                $response["task"] = $result["task"];
//                $response["status"] = $result["status"];
//                $response["createdAt"] = $result["created_at"];
//                echoRespnse(200, $Responce);
    } else {
        $Responce->setError(true);
        $Responce->setMessage(END_OF_POSTS);
        // $response["error"] = true;
        // $response["message"] = "The requested resource doesn't exists";
//                echoRespnse(404, $response);
    }

    echoRespnse(201, $Responce->setArray());
});




$app->get('/get_dashboard/:language_id', 'authenticate', 
        
        function($language_id) {
    global $user_id;


    $response = array();
    $db = new DbHandler();

    $Responce = new Responce();
			
    $resultLanguages = $db->getLanguages();
//    $resultLatest = $db->getPosts($user_id, null,$language_id, TRUE, FALSE,1,POST_LIMIT);
//    $resultTop = $db->getPosts($user_id, null,$language_id, FALSE, TRUE,1,POST_LIMIT);
	$resultCategories = $db->getCategories($language_id);
	$resultImages = $db->getImages();
	$resultCardColors = $db->getCardColors();
//        $resultTranslation = $db->getTranslations();


	

    if ($resultLanguages != NULL) {
        $Responce->setError(false);
        $Responce->setMessage("false");
                
        $Responce->setData('languages', $resultLanguages);
//        $Responce->setData('latest', $resultLatest);
//        $Responce->setData('top', $resultTop);
        $Responce->setData('categories', $resultCategories);
	$Responce->setData('settings', $resultImages);
	$Responce->setData('card_colors', $resultCardColors);
//	$Responce->setData('translations', $resultTranslation);


    } else {     
        $Responce->setError(false);
        $Responce->setMessage("No Data");
    }

    echoRespnse(201, $Responce->setArray());
});

$app->get('/get_languages/','authenticate',

		function()
		{
			global $user_id;
			$response = array();
			$db = new DbHandler();
			$Responce = new Responce();

			// fetch task
			$result = $db->getLanguages();

			if ($result != NULL) {
				$Responce->setError(false);
				$Responce->setMessage("false");
				$Responce->setData('languages',$result);


			} else {
				$Responce->setError(false);
				$Responce->setMessage("false");
		
			}

			echoRespnse(201, $Responce->setArray());
});
$app->get('/get_categories/','authenticate',

		function()
		{
			global $user_id;


			$response = array();
			$db = new DbHandler();

			$Responce = new Responce();

			// fetch task
			$result = $db->getCategories();

			if ($result != NULL) {
				$Responce->setError(false);
				$Responce->setMessage("false");
				$Responce->setData('categories',$result);


			} else {
				$Responce->setError(false);
				$Responce->setMessage("false");
		
			}

			echoRespnse(201, $Responce->setArray());
});
        
$app->get('/get_authors/:sort','authenticate',

		function($sort)
		{
			global $user_id;


			$response = array();
			$db = new DbHandler();

			$Responce = new Responce();

			// fetch task
			$result = $db->getAuthors($sort);

			if ($result != NULL) {
				$Responce->setError(false);
				$Responce->setMessage("false");
				$Responce->setData('authors',$result);


			} else {
				$Responce->setError(false);
				$Responce->setMessage("false");

			}

			echoRespnse(201, $Responce->setArray());
});
        
        
$app->get('/tasks', 'authenticate', function() {
            global $user_id;
            $response = array();
            $db = new DbHandler();

            // fetching all user tasks
            $result = $db->getAllUserTasks($user_id);

            $response["error"] = false;
            $response["tasks"] = array();

            // looping through result and preparing tasks array
            while ($task = $result->fetch_assoc()) {
                $tmp = array();
                $tmp["id"] = $task["id"];
                $tmp["task"] = $task["task"];
                $tmp["status"] = $task["status"];
                $tmp["createdAt"] = $task["created_at"];
                array_push($response["tasks"], $tmp);
            }

            echoRespnse(200, $response);
        });

/**
 * Listing single task of particual user
 * method GET
 * url /tasks/:id
 * Will return 404 if the task doesn't belongs to user
 */
$app->get('/tasks/:id', 'authenticate', function($task_id) {
            global $user_id;
            $response = array();
            $db = new DbHandler();

            // fetch task
            $result = $db->getTask($task_id, $user_id);

            if ($result != NULL) {
                $response["error"] = false;
                $response["id"] = $result["id"];
                $response["task"] = $result["task"];
                $response["status"] = $result["status"];
                $response["createdAt"] = $result["created_at"];
                echoRespnse(200, $response);
            } else {
                $response["error"] = true;
                $response["message"] = "The requested resource doesn't exists";
                echoRespnse(404, $response);
            }
        });

/**
 * Creating new task in db
 * method POST
 * params - name
 * url - /tasks/
 */
$app->post('/tasks', 'authenticate', function() use ($app) {
            // check for required params
            verifyRequiredParams(array('task'));

            $response = array();
            $task = $app->request->post('task');

            global $user_id;
            $db = new DbHandler();

            // creating new task
            $task_id = $db->createTask($user_id, $task);

            if ($task_id != NULL) {
                $response["error"] = false;
                $response["message"] = "Task created successfully";
                $response["task_id"] = $task_id;
                echoRespnse(201, $response);
            } else {
                $response["error"] = true;
                $response["message"] = "Failed to create task. Please try again";
                echoRespnse(200, $response);
            }            
        });

/**
 * Updating existing task
 * method PUT
 * params task, status
 * url - /tasks/:id
 */
$app->put('/tasks/:id', 'authenticate', function($task_id) use($app) {
            // check for required params
            verifyRequiredParams(array('task', 'status'));

            global $user_id;            
            $task = $app->request->put('task');
            $status = $app->request->put('status');

            $db = new DbHandler();
            $response = array();

            // updating task
            $result = $db->updateTask($user_id, $task_id, $task, $status);
            if ($result) {
                // task updated successfully
                $response["error"] = false;
                $response["message"] = "Task updated successfully";
            } else {
                // task failed to update
                $response["error"] = true;
                $response["message"] = "Task failed to update. Please try again!";
            }
            echoRespnse(200, $response);
        });

/**
 * Deleting task. Users can delete only their tasks
 * method DELETE
 * url /tasks
 */
$app->delete('/tasks/:id', 'authenticate', function($task_id) use($app) {
            global $user_id;

            $db = new DbHandler();
            $response = array();
            $result = $db->deleteTask($user_id, $task_id);
            if ($result) {
                // task deleted successfully
                $response["error"] = false;
                $response["message"] = "Task deleted succesfully";
            } else {
                // task failed to delete
                $response["error"] = true;
                $response["message"] = "Task failed to delete. Please try again!";
            }
            echoRespnse(200, $response);
        });

/**
 * Verifying required params posted or not
 */
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
 * Validating email address
 */
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

$app->run();
?>