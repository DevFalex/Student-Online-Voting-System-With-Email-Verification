-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2020 at 02:48 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `evoting`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE IF NOT EXISTS `tbladmin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(255) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(68) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`admin_id`, `fname`, `mname`, `lname`, `username`, `password`) VALUES
(1, 'admin', 'admin', 'admin', 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918');

-- --------------------------------------------------------

--
-- Table structure for table `tblcandidate`
--

CREATE TABLE IF NOT EXISTS `tblcandidate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `studentid` int(11) NOT NULL,
  `partyid` int(11) NOT NULL,
  `candidatepositionid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `partyid` (`partyid`),
  KEY `candidatepositionid` (`candidatepositionid`),
  KEY `candidatepositionid_2` (`candidatepositionid`),
  KEY `studentid` (`studentid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=94 ;

--
-- Dumping data for table `tblcandidate`
--

INSERT INTO `tblcandidate` (`id`, `studentid`, `partyid`, `candidatepositionid`) VALUES
(76, 128, 16, 1),
(77, 130, 16, 2),
(78, 129, 16, 3),
(79, 131, 16, 3),
(80, 133, 17, 2),
(81, 134, 17, 3),
(82, 128, 18, 1),
(83, 129, 18, 2),
(84, 130, 18, 3),
(85, 131, 19, 1),
(86, 133, 19, 2),
(87, 134, 19, 3),
(92, 128, 20, 1),
(93, 129, 21, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblcandidateposition`
--

CREATE TABLE IF NOT EXISTS `tblcandidateposition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `positionname` varchar(30) NOT NULL,
  `sortorder` int(5) NOT NULL,
  `votesallowed` int(5) NOT NULL,
  `allowperparty` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tblcandidateposition`
--

INSERT INTO `tblcandidateposition` (`id`, `positionname`, `sortorder`, `votesallowed`, `allowperparty`) VALUES
(1, 'President', 1, 1, 1),
(2, 'Vice President', 2, 1, 1),
(3, 'Senator', 3, 3, 12);

-- --------------------------------------------------------

--
-- Table structure for table `tblcourse`
--

CREATE TABLE IF NOT EXISTS `tblcourse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `courseinitial` varchar(8) NOT NULL,
  `coursename` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `tblcourse`
--

INSERT INTO `tblcourse` (`id`, `courseinitial`, `coursename`) VALUES
(11, 'BSIT', 'Bachelor of Science in Information Technology'),
(12, 'BSBA', 'Bachelor of Science in Business Administration'),
(13, 'BSHRM', 'Bachelor of Science in Hotel and Restaurant Management'),
(14, 'BSHM', 'Bachelor of Science in Hospitality Management'),
(15, 'BSCRIM', 'Bachelor of Science in Criminology'),
(16, 'BSCS', 'Bachelor of Science in Computer Science');

-- --------------------------------------------------------

--
-- Table structure for table `tblparty`
--

CREATE TABLE IF NOT EXISTS `tblparty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partyinitial` varchar(11) NOT NULL,
  `partyname` varchar(100) NOT NULL,
  `party_election_date_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `tblparty`
--

INSERT INTO `tblparty` (`id`, `partyinitial`, `partyname`, `party_election_date_id`) VALUES
(9, 'likes', 'likes', 1),
(10, 'secret', 'secret', 1),
(11, 'bon', 'bon', 2),
(12, '23213', 'adasd', 10),
(13, 'zxc', 'zxc', 10),
(14, 'xxx', 'xxx', 10),
(15, 'yyy', 'yyy', 10),
(16, 'Fresh', 'Team Fresh', 23),
(17, 'bon', 'BON BON', 23),
(18, 'qqq', 'qqq', 30),
(19, 'bon', 'BonBon', 30),
(20, 'Party 2', 'Party List 2', 33),
(21, 'Party1', 'Party List 1', 33);

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent`
--

CREATE TABLE IF NOT EXISTS `tblstudent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idno` varchar(15) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `middlename` varchar(30) NOT NULL,
  `courseid` int(5) DEFAULT NULL,
  `image` varchar(30) NOT NULL,
  `votingcode` varchar(15) DEFAULT NULL,
  `votestatus` char(1) DEFAULT NULL COMMENT '0 - not voted, 1 - voted',
  `yearlevelid` int(12) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `votingcode` (`votingcode`),
  KEY `courseid` (`courseid`),
  KEY `yearlevelid` (`yearlevelid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=136 ;

--
-- Dumping data for table `tblstudent`
--

INSERT INTO `tblstudent` (`id`, `idno`, `lastname`, `firstname`, `middlename`, `courseid`, `image`, `votingcode`, `votestatus`, `yearlevelid`) VALUES
(128, '2345-2345', 'Peter', 'John', 'M', 16, 'Doctor-Consultation.png', 'GOA-539C73', '0', 13),
(129, '2345-23', 'Curry', 'Stephen', 'H', 11, '8196186971_2237f161bd_b.jpg', 'URX-54CBB8', '0', 13),
(130, '235-1235', 'Meyer', 'Jane', 'F', 11, '', 'IRW-6F1595', '0', 13),
(131, '2345-23', 'Escobar', 'Mars', 'T', 15, '', 'QRG-3A6D8F', '0', 12),
(133, '235-23', 'James', 'Kate', 'M', 11, '', 'THE-C74B9A', '0', 13),
(134, '5423-23', 'Miranda', 'Ching', 'P', 12, 'images (16).jpg', 'UFJ-677465', '0', 13),
(135, '123-456', 'Doe', 'John', 'x', 12, '', 'IRA-0EA059', '0', 12);

-- --------------------------------------------------------

--
-- Table structure for table `tblvotes`
--

CREATE TABLE IF NOT EXISTS `tblvotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `candidateid` int(11) NOT NULL,
  `daterecorded` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `candidateid` (`candidateid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=90 ;

--
-- Dumping data for table `tblvotes`
--

INSERT INTO `tblvotes` (`id`, `candidateid`, `daterecorded`) VALUES
(88, 92, '2019-05-17 11:42:30'),
(89, 92, '2019-08-29 15:49:38');

-- --------------------------------------------------------

--
-- Table structure for table `tblvotestatus`
--

CREATE TABLE IF NOT EXISTS `tblvotestatus` (
  `vote_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_status_election_date_id` int(11) NOT NULL,
  `vote_status_studentid` int(11) NOT NULL,
  PRIMARY KEY (`vote_status_id`),
  KEY `vote_status_election_date_id` (`vote_status_election_date_id`,`vote_status_studentid`),
  KEY `vote_status_studentid` (`vote_status_studentid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `tblvotestatus`
--

INSERT INTO `tblvotestatus` (`vote_status_id`, `vote_status_election_date_id`, `vote_status_studentid`) VALUES
(33, 33, 129),
(32, 33, 134);

-- --------------------------------------------------------

--
-- Table structure for table `tblyearlevel`
--

CREATE TABLE IF NOT EXISTS `tblyearlevel` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `yearlevelinitial` varchar(10) NOT NULL,
  `yearlevelname` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `tblyearlevel`
--

INSERT INTO `tblyearlevel` (`id`, `yearlevelinitial`, `yearlevelname`) VALUES
(9, 'JR-GR10', 'Grade 10'),
(10, 'SR-GR11', 'Grade 11'),
(11, 'SR-GR12', 'Grade 12'),
(12, 'COL-1ST-YR', 'First Year'),
(13, 'COL-2ND-YR', 'Second Year'),
(15, 'COL-4TH-YR', 'Fourth Year'),
(16, 'COL-5TH-YR', 'Fifth Year');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_election_date`
--

CREATE TABLE IF NOT EXISTS `tbl_election_date` (
  `election_date_id` int(11) NOT NULL AUTO_INCREMENT,
  `election_date` date NOT NULL,
  PRIMARY KEY (`election_date_id`),
  UNIQUE KEY `election_date` (`election_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `tbl_election_date`
--

INSERT INTO `tbl_election_date` (`election_date_id`, `election_date`) VALUES
(33, '2019-08-29');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblcandidate`
--
ALTER TABLE `tblcandidate`
  ADD CONSTRAINT `tblcandidate_ibfk_2` FOREIGN KEY (`partyid`) REFERENCES `tblparty` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblcandidate_ibfk_3` FOREIGN KEY (`candidatepositionid`) REFERENCES `tblcandidateposition` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblcandidate_ibfk_4` FOREIGN KEY (`studentid`) REFERENCES `tblstudent` (`id`);

--
-- Constraints for table `tblstudent`
--
ALTER TABLE `tblstudent`
  ADD CONSTRAINT `tblstudent_ibfk_1` FOREIGN KEY (`courseid`) REFERENCES `tblcourse` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tblstudent_ibfk_2` FOREIGN KEY (`yearlevelid`) REFERENCES `tblyearlevel` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tblvotes`
--
ALTER TABLE `tblvotes`
  ADD CONSTRAINT `tblvotes_ibfk_1` FOREIGN KEY (`candidateid`) REFERENCES `tblcandidate` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblvotestatus`
--
ALTER TABLE `tblvotestatus`
  ADD CONSTRAINT `tblvotestatus_ibfk_1` FOREIGN KEY (`vote_status_election_date_id`) REFERENCES `tbl_election_date` (`election_date_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tblvotestatus_ibfk_2` FOREIGN KEY (`vote_status_studentid`) REFERENCES `tblstudent` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
