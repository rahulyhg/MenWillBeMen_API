-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2017 at 11:32 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mwbm`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id_category` int(11) NOT NULL,
  `id_language` int(11) NOT NULL,
  `category_icon` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `category_name` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id_category`, `id_language`, `category_icon`, `category_name`, `created_on`) VALUES
(1, 2, 'http://192.168.0.101/menwillbemen_web/images/icon/home-icon.png', 'चावट जोक्स 😂😂', '2017-05-06 10:53:37'),
(2, 2, 'http://192.168.0.101/menwillbemen_web/images/icon/home-icon.png', 'नवरा बायको', '2017-05-06 10:53:40'),
(3, 2, 'http://192.168.0.101/menwillbemen_web/images/icon/home-icon.png', 'टाईमपास', '2017-05-06 10:53:43'),
(4, 1, 'http://192.168.0.101/menwillbemen_web/images/icon/home-icon.png', 'TimePass', '2017-05-06 10:53:43');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id_language` int(11) NOT NULL,
  `language_title` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id_language`, `language_title`) VALUES
(1, 'English'),
(2, 'Marathi');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id_post` int(11) NOT NULL,
  `id_language` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_category` int(11) NOT NULL,
  `post` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `post_likes_count` int(11) NOT NULL,
  `post_whatsapp_count` int(20) NOT NULL,
  `post_share_count` int(20) NOT NULL,
  `post_created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id_post`, `id_language`, `id_user`, `id_category`, `post`, `post_likes_count`, `post_whatsapp_count`, `post_share_count`, `post_created_on`, `status`) VALUES
(1, 2, 2, 1, 'बायकांकडे अशी कोणती गोष्ट असते जी आकाराने गोल असते,शरीराच्या दोन्ही बाजूस असते, बाई चालतान्ना ती गोष्ट हलते आणि त्या गोष्टीचे नाव ब पासून सूरू होते???\n\n??\n\n??\n\n??\n\n??\n\nबांगड्या !\nकधीतरी चांगला विचार करा रे\n😂😂😂😂😂', 155, 8, 3, '2017-05-07 08:51:28', 1),
(2, 2, 3, 1, 'तु दीसलीस की ताठ होतो माझा,\n.\n.\n.\n.\n.\n.\n.\n''स्वाभिमान''\n.\n.\nतु हासलीस की फवारा उडतो माझ्या,\n.\n.\n.\n.\n.\n.\n.\n.''मनातुन आनंदाचा''\n.\n.\nतु बोललीस कि वाटते घालावा तुझ्या तोंडात,\n.\n.\n.\n.\n''पेढ्याचा घास''\n.\n.\nतु लाजलीस की वाटते दाबावे तुझे दोन्ही,\n.\n.\n.\n.\n.\n.\n\n''गाल''\n.\nआणि,\nतु निघुन गेल्यावर वाटते हालवत रहावा\nवर करुन,\n.\n.\n.\n''हात''\nबाय बाय करण्यासाठी.', 98, 7, 1, '2017-05-07 08:51:28', 1),
(3, 2, 2, 2, 'जपानी जोडपे मध्य रात्री सेक्स विषयी बोलत असतात...\n\nनवरा: सुटाकी...\n\nबायको: कोवानीनी...\n\nनवरा: टोका अनजी रोडी रोमी होआ याको ...\n\nबायको गुढग्यावर बसून बोलली : मिमी योआ नकोडींडा टिंकूजी...\n\nनवरा: ना मिआयू किना टिम कौजी...\n\nबायको:सू की किनी माटो...\n\nनवरा:सको टिटी यान्वी...\n\n.\n\n.\n\n.\n\n.\n\nबायको :\n\n.\n\n.\n\n.\n\n.\n\nमम्म्म्म्म्म...\n\n.\n\n.\n\n.\n\n.\n\n.\n\n.\n\n.\n\n.\n\n.\n\nनवरा: येची कोबा नाटी...\n\n.\n\n.\n\n.\n\n.\n\n.\n\n.\n\nबायको :\n\n.\n\n.\n\n.\n\n.\n\n.\n\n.\n\nलोकांची हौस तर बघा,\n\nसमजत तर काही नाही, फक्त sex शब्दा साठी पुर्न मेसेज वाचला...\n\n.\n\nसुधरा हरामखोरांनो...\n\nकामा धंद्यात लक्ष्य घाला...!!!', 40, 10, 0, '2017-05-07 08:51:28', 1),
(4, 2, 1, 2, 'मास्तर- राहुल गांधी अविवाहित का आहे??\n\nगण्या- कारण त्याचा हातावर विश्वास आहे...', 123, 3, 0, '2017-05-07 08:51:28', 1),
(5, 2, 1, 2, 'गुरूजी : मुलांनो, स्कॉलरशिपच्या फॉर्म मध्ये नाव लिहीण्याची जागा छोटी असल्या कारणाने, तुम्ही आप आपली नावे संपूर्ण न लिहीता शॉर्ट मध्ये लिहावे.\nउदारर्णार्थ "किशोर विलास पाटील" हे नाव तुम्ही शॉर्ट मध्ये "कि. वि. पाटील'' असे लिहावे.\n\nविध्यार्थी : माझं नाव शॉर्ट मध्ये लिहीणे अवघड जाईल, सर.\n\nगुरूजी : का ? काय आहे तुझे नाव ?\n\nविध्यार्थी : पुरशोत्तम चिराग चाटे ....\nगुरूजींनी फॉर्म जाळून टाकले.', 123, 8, 0, '2017-05-07 08:51:28', 1),
(6, 2, 1, 3, 'boyfriend - तू माझ्या जीवनातला चंद्र आहेस…!!girlfriend - आणि तू माझा नील आर्मस्ट्राँग...,\n\nboyfriend - म्हणजे ?\n\ngirlfriend - माझ्यावर चढणारा पहिला माणूस', 123, 3, 4, '2017-05-07 08:51:28', 1),
(7, 2, 1, 3, 'गुरुजी : सांग बर गणु डास चावल्याने\nएडस होतो का?गणु: नाहि होत गुरुजी....गुरुजी: शाब्बास!\nआता सांग का नाही होत?गणु: कारण गुरुजी डास माणसाला\n''चावत'' असतात,\n''झवत'' नसतात......\nकायपण विचारतो येडझवा.', 123, 7, 7, '2017-05-07 08:51:28', 1),
(8, 2, 1, 3, 'लवडया वर निबंध''लवड़ा'' शब्दच किती निराला आहे ना?\nनिसर्गाने दिलेली अमूल्य भेट म्हणजे लवड़ा.\n\nलवड़ा बिचारा नेहमी कोंड़्लेला असतो.म्हणून तो जेव्हा बाहेर येतो तर काही ना कही चाळे केल्या शिवाय राहत नाही.थंडीत तो नेहमी एक्टिव असतो.आणि एकदा उठल्यावर तो कोणाच्या बापाचा नसतो.\n\nकोणाचा लवड़ा छोटा कोणाचा मोठा तर कोणाचा वाकडा पण असतो.आणि कोणा कोणाची तर छोटीशी चिंगळी असते.त्याला नेहमी एक कवच असत तो कवच काढल्यावर त्याच टक्कल बाहेर येत.\n\nलवडयाला इंग्लिश मधे पेनीस,कॉक,डिक असे म्हणतात आणि लाडाने नुन्नू असेही म्हणतात.\nहा जीवनाचा अविभाज्य घटक आहे, लवड़ा नसता तर जगच नसत.लवडया खाली दोन गोट्या असतात.पण त्या खेळायला नसतात आणि odonil च्या पण नसतात.\n\nलवडया भोवती केस असतात त्या मुळे त्याला सॉफ्ट वाटते.\nतो मुड नुसार आकार बदलत असतो कधी छोटा तर कधी मोठा.पण तो उभा राहिला तर व्यायाम केल्या शिवाय झोपत नाही.\n\nत्याला पुच्ची खुप आवडते.\nतो तीच्या साठी काही पण करतो.\nपुच्चित घुस्ल्यावर त्याला स्वर्गसुख मीळते.\n\nलवडयाची एक चांगली सवय अशी की तो सकाळी आपल्या पहिलेच उठलेला असतो.\nत्याला गझर(alarm)ची गरज पडत नाही.\n\nतो मुलींची खुप इज्जत करतो.त्यांना बघितल्या वर तो लगेच उभ राहून सलाम ठोकतो.\nतसे मुलिन्नाही तो खुप आवडतो.\nत्या त्याच्याशी खुप खेळतात.\n\nबॉल मधे अडकवून वर खाली केल्यावर त्यांना मज्जा वाटते कधी कधी तो जास्त थकल्या मुळे लवकर उभा राहत नाही.तो जेव्हा व्यायाम करतो तेव्हा तो थूकतो.\n\nरोज सकाळचा वेळ त्याला आणि त्याच्या दोन गोट्यान्ना गोंज़ार्न्यात\nजातो.लवडयावर कितीही बोलले तरी शब्द अपुरेच पडतील.\n\nप्रत्येकाला जीवनात एक तरी लवड़ा असावा.मला माझा लवड़ा खुप आवडतो.ज्याला लवडा आहे त्याने पुढे सेंड करा नाही त्यांनी गप्प बसा.', 123, 11, 14, '2017-05-07 08:51:28', 1),
(9, 2, 1, 3, 'Marathi Non Veg Ukhane\n\n१)लग्नाच्या पहिल्या राञि सजवलि आमची खोली...रावांचे नाव घेते\nगांड माझी ओली...\n\n२)सासर माझ खानदानी नवरा माझा राजा,\nरावांचा ऊठत नाही,\nमग सासरे वाजवतात बाजा.\n\n३)पारिजातांच्या फूलांचा छान पडलाय सडा,\nरावांचे नाव घेते,\nएकदा तरी चडा.\n\n४)बांधावरून चालतांना पाय घसरुन पडले,\nआमचे मादरचोद,\nतेथेच माझ्यावर चडले.\n\n५)समूद्रात पोहताना चड्डी गेली वाहुन,\nहे गेले शोधायला आणी बाकीचे गेले झवून.\n\n६)ऊन्हाळ्याच्या दिवसात विहीर आमची आटली,\nयांनी रोज झवुन गांड माझी फाटली.\n\n७)कामावरुन आल्यावर हे होते जेवत,\nखोलीत जावून बघते तर सासुसासरे होते झवत.\n\n८)मूड झाला म्हणुन साडी यांनी सोडली,\nतसेच लागले झवायला,\nचड्डीच नाय काडली.\n\n९)जाडे असले तरी मिठित माझ्या मावतात,\nरावांचे नाव घेते,\nरोज मला झवतात.\n\n१०)दारू पिऊन आल्यावर हे राहतात पडुन,\nमग काय करणार राव,\nसासरे जातात चडुन.', 123, 24, 25, '2017-05-07 08:51:28', 1),
(10, 2, 1, 1, 'संता (नौकर से) – ज़रा देख तो बाहर सूरज निकला या नहीं ?\r\nनौकर – बाहर तो अँधेरा है !\r\nसंता – अरे तो टॉर्च जलाकर देख ले कामचोर 😆😎😎😜', 123, 11, 14, '2017-05-07 08:51:28', 1),
(11, 2, 3, 1, 'Santa(संता): हम पति-पत्नी तमिल सीखना चाहते हैं!\r\nBanta(बंता): वो क्यों?\r\nSanta(संता): हमने एक तमिल बच्चा गोद लिया है!हम सोचते हैं जब वो बोलने लगे तो उससे पहले पहले हम तमिल सीख लें!😬😂﻿', 98, 8, 1, '2017-05-07 08:51:28', 1),
(12, 1, 2, 4, 'Helloo', 155, 9, 3, '2017-05-07 08:53:07', 1),
(13, 1, 2, 4, 'hiii', 155, 10, 6, '2017-05-07 08:53:09', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quotes_tags`
--

CREATE TABLE `quotes_tags` (
  `tag_id` int(11) NOT NULL,
  `quote_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) CHARACTER SET utf8 NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`, `status`) VALUES
(1, 'footer_image', '1', 1),
(2, 'left_header_image', 'http://192.168.0.101/menwillbemen_web/images/background/header.jpg', 1),
(3, 'right_header_image', 'http://192.168.0.101/menwillbemen_web/images/background/header.jpg', 1),
(4, 'color-blue', '#FF33B5E5', 0),
(5, 'color-red', '#FFFF4444', 1),
(6, 'background_image', 'http://192.168.0.101/menwillbemen_web/images/background/pow-star.png', 1),
(7, 'language_en', 'English', 1),
(8, 'language_ma', 'मराठी', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `tag_id` int(11) NOT NULL,
  `tag_title` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `task` text NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` text NOT NULL,
  `api_key` varchar(32) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `api_key`, `status`, `created_at`) VALUES
(1, 'guest', 'guest@guest.com', '$2a$10$5af01ca52dc386f6db472Oibc8c0motGEHT6TKR0ispt.YKIi9RH.', 'guest', 1, '2017-01-22 16:43:37'),
(2, 'admin', 'admin@admin.com', '$2a$10$5af01ca52dc386f6db472Oibc8c0motGEHT6TKR0ispt.YKIi9RH.', 'admin', 1, '2017-01-22 16:43:37');

-- --------------------------------------------------------

--
-- Table structure for table `user_tasks`
--

CREATE TABLE `user_tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id_language`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id_post`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_tasks`
--
ALTER TABLE `user_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `task_id` (`task_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id_language` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user_tasks`
--
ALTER TABLE `user_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
