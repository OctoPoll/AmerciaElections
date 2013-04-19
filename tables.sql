-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: mysql51-013.wc1:3306
-- Generation Time: Apr 18, 2013 at 09:01 PM
-- Server version: 5.1.61
-- PHP Version: 5.2.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `711860_voteamercia`
--

-- --------------------------------------------------------

--
-- Table structure for table `elections`
--

CREATE TABLE `elections` (
  `election_id` int(11) NOT NULL AUTO_INCREMENT,
  `election_keyword` varchar(20) NOT NULL,
  `election_owner` int(11) DEFAULT NULL,
  `election_start` datetime DEFAULT NULL,
  `election_end` datetime DEFAULT NULL,
  PRIMARY KEY (`election_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `election_candidates`
--

CREATE TABLE `election_candidates` (
  `candidate_id` int(11) NOT NULL AUTO_INCREMENT,
  `candidate_name` varchar(20) NOT NULL,
  `candidate_election_id` int(11) NOT NULL,
  `candidate_is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`candidate_id`),
  UNIQUE KEY `u_candidate_name` (`candidate_name`,`candidate_election_id`),
  KEY `candidate_election_id` (`candidate_election_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

-- --------------------------------------------------------

--
-- Table structure for table `exit_poll_results`
--

CREATE TABLE `exit_poll_results` (
  `result_id` int(11) NOT NULL AUTO_INCREMENT,
  `result_question` int(11) NOT NULL,
  `result_answer` int(11) NOT NULL,
  `user_id` varchar(100) NOT NULL,
  `result_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`result_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2756 ;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `vote_id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_candidate_id` int(11) NOT NULL,
  `user_id` varchar(100) DEFAULT NULL,
  `vote_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`vote_id`),
  KEY `user_id` (`user_id`,`vote_candidate_id`),
  KEY `vote_candidate_id` (`vote_candidate_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=552 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `election_candidates`
--
ALTER TABLE `election_candidates`
  ADD CONSTRAINT `election_candidates_ibfk_1` FOREIGN KEY (`candidate_election_id`) REFERENCES `elections` (`election_id`);

--
-- Constraints for table `exit_poll_results`
--
ALTER TABLE `exit_poll_results`
  ADD CONSTRAINT `exit_poll_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `votes` (`user_id`);

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`vote_candidate_id`) REFERENCES `election_candidates` (`candidate_id`);
