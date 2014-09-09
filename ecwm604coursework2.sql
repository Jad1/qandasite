-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 12, 2014 at 11:34 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ecwm604coursework2`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE IF NOT EXISTS `answer` (
  `AnswerID` int(11) NOT NULL AUTO_INCREMENT,
  `QuestionID` int(11) NOT NULL,
  `Content` varchar(10000) NOT NULL,
  `PostedBy` varchar(50) NOT NULL,
  `PostedOn` datetime NOT NULL,
  `LastEditedBy` varchar(50) DEFAULT NULL,
  `LastEditedOn` varchar(50) DEFAULT NULL,
  `AnswerRating` int(11) NOT NULL,
  PRIMARY KEY (`AnswerID`),
  KEY `PostedBy` (`PostedBy`),
  KEY `LastEditedBy` (`LastEditedBy`),
  KEY `QuestionID` (`QuestionID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`AnswerID`, `QuestionID`, `Content`, `PostedBy`, `PostedOn`, `LastEditedBy`, `LastEditedOn`, `AnswerRating`) VALUES
(1, 1, 'You need to specify what you are separating your words with so that the method knows how to split the string.', 'admin', '2014-01-03 23:20:26', 'admin', '2014-01-12 16:47:25', 0),
(2, 1, 'You need to tell the explode method how you are separating your future elements. Then it knows what to look for when splitting up the string.', 'mod', '2014-01-03 23:20:35', 'admin', '2014-01-12 16:51:07', 0),
(13, 13, 'I''m not sure that you can do this. Why do you want to do it anyway?', 'mod', '2014-01-11 22:43:34', '', '', 0),
(6, 3, 'If you are struggling with this then why not use the get and post equivalents that jQuery provide?', 'mod', '2014-01-03 23:21:54', 'mod', '2014-01-12 16:17:34', 0),
(7, 4, 'You can use the date class which is provided in PHP. It constructs a date or time in whatever way you want. See the API for more info.', 'user', '2014-01-03 23:22:04', 'user', '2014-01-12 01:21:55', 0),
(8, 4, 'The date class allows you to set dates, times or both. You can customise the format to your liking.', 'mod', '2014-01-03 23:22:09', 'admin', '2014-01-12 16:52:57', 0),
(10, 5, 'What programming language are you using? It will be easier to help you if I know this.', 'user', '2014-01-03 23:22:24', 'user', '2014-01-12 16:11:01', 0),
(11, 1, 'Put in the delimiter and then the string that you want to turn into an array.', 'admin', '2014-01-07 10:38:10', 'admin', '2014-01-12 16:49:55', 0),
(14, 7, 'You should look at the img tag that HTML uses for images.', 'user', '2014-01-12 15:48:56', '', '', 0),
(15, 5, 'If you are using PHP then consider CodeIgniter as a framework. It prevents SQL injection.', 'user', '2014-01-12 16:08:41', '', '', 0),
(16, 3, 'Make sure you pass in the URL and the data you want to pass in. Use the built in success and error methods too.', 'mod', '2014-01-12 16:16:30', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `QuestionID` int(11) NOT NULL AUTO_INCREMENT,
  `Category` varchar(50) NOT NULL,
  `Title` varchar(500) NOT NULL,
  `Content` varchar(10000) NOT NULL,
  `PostedBy` varchar(50) NOT NULL,
  `PostedOn` datetime NOT NULL,
  `LastEditedBy` varchar(50) DEFAULT NULL,
  `LastEditedOn` datetime DEFAULT NULL,
  `Tags` varchar(75) NOT NULL,
  `Replies` int(11) NOT NULL,
  PRIMARY KEY (`QuestionID`),
  KEY `LastEditedBy` (`LastEditedBy`),
  KEY `PostedBy` (`PostedBy`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`QuestionID`, `Category`, `Title`, `Content`, `PostedBy`, `PostedOn`, `LastEditedBy`, `LastEditedOn`, `Tags`, `Replies`) VALUES
(1, 'PHP', 'PHP explode() array not working?', 'I am trying to use the explode() array to turn a string into an array, but it isnt working.', 'user', '2014-01-12 01:00:00', 'admin', '2014-01-12 01:56:08', 'php, array', 3),
(3, 'JavaScript', 'jQuery ajax method not working?', 'I am trying to use $.ajax to try to perform an AJAX request using jQuery but I am having little luck so far. Help me!!!', 'admin', '2014-01-12 03:00:00', NULL, NULL, 'javascript, ajax, jquery', 2),
(4, 'PHP', 'Using dates and times in php', 'How do you construct a date and time in PHP? It''d be nice to see an example!', 'admin', '2014-01-12 04:00:00', NULL, NULL, 'php, date, time', 2),
(5, 'Web Security', 'SQL Injection Attack', 'Help! My database is being hacked into. Hackers are stealing data or even deleting it!', 'mod', '2014-01-12 05:38:58', NULL, NULL, 'security, sql injection', 2),
(7, 'HTML', 'HTML image', 'How do you add an image in HTML? I cannot find any information about this', 'admin', '2014-01-12 15:39:19', NULL, NULL, 'html, image', 1),
(8, 'PHP', 'Create a class in codeigniter', 'How do you create a class in codeigniter? I cannot find any information about this', 'mod', '2014-01-12 15:41:50', NULL, NULL, 'php, codeiginter, class', 0),
(9, 'PHP', 'java newb to JavaScript programming', 'I am a Java programmer new to JavaScript. Are there any useful tutorials to get started with JavaScript?', 'user', '2014-01-12 15:45:22', NULL, NULL, 'javascript, java, programming', 0),
(13, 'CSS', 'Store CSS in a database', 'Can you store CSS in a database?  I cannot find any information about this.', 'user', '2014-01-12 15:46:22', 'mod', '2014-01-11 22:42:25', 'CSS, database', 1),
(11, 'HTML', 'Select value by default', 'How do you tell a select element to pick a value by default? I cannot find any information about this', 'admin', '2014-01-12 15:48:22', NULL, NULL, 'HTML, select', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `UserId` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(40) NOT NULL,
  `EmailAddress` varchar(50) NOT NULL,
  `FavWebDevLanguage` varchar(50) DEFAULT NULL,
  `FavBrowser` varchar(50) DEFAULT NULL,
  `CurrentProjects` varchar(500) DEFAULT NULL,
  `Reputation` int(11) NOT NULL,
  `Flag` int(11) NOT NULL,
  PRIMARY KEY (`UserId`),
  UNIQUE KEY `Username` (`Username`),
  UNIQUE KEY `Username_2` (`Username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserId`, `Username`, `Password`, `EmailAddress`, `FavWebDevLanguage`, `FavBrowser`, `CurrentProjects`, `Reputation`, `Flag`) VALUES
(12, 'mod', '5a72c1685589c5dccf8ae29275b53f2133807c0e', 'mod@modmail.co.uk', 'PHP', 'Firefox', 'PHP is fun.', 10, 2),
(11, 'admin', '8dbeedbd419adeca3797ccaf9db1c4629a34d810', 'admin@yahoo.com', 'CSS', 'Safari', 'I like CSS.', 10, 3),
(10, 'user', 'aa0dbae5a3e8a1998c1461541a4dcb9a41f2589a', 'user@hotmail.com', 'HTML', 'Internet Explorer', 'Developing a question and answer forum.', 10, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
