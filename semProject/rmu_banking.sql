--
-- Database: `rmu_banking`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_details`
--

DROP TABLE IF EXISTS `account_details`;
CREATE TABLE IF NOT EXISTS `account_details` (
  `accID` int(11) AUTO_INCREMENT PRIMARY KEY,
  `account_number` varchar(20) DEFAULT NULL,
  `pin` int(11) DEFAULT NULL,
  `amount` decimal(10,0) DEFAULT NULL,
  `momo_number` varchar(10) DEFAULT NULL,
  `user_ID` int(11) DEFAULT NULL,
  PRIMARY KEY (`accID`),
  KEY `user_ID` (`user_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `b2b`
--

DROP TABLE IF EXISTS `b2b`;
CREATE TABLE IF NOT EXISTS `b2b` (
  `transID` int(11) AUTO_INCREMENT PRIMARY KEY,
  `bank_name` varchar(40) DEFAULT NULL,
  `account_number` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`transID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `b2mm`
--

DROP TABLE IF EXISTS `b2mm`;
CREATE TABLE IF NOT EXISTS `b2mm` (
  `transid` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `b2om`
--

DROP TABLE IF EXISTS `b2om`;
CREATE TABLE IF NOT EXISTS `b2om` (
  `transID` int(11) AUTO_INCREMENT PRIMARY KEY,
  `network` varchar(10) DEFAULT NULL,
  `phone_number` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`transID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `charges`
--

DROP TABLE IF EXISTS `charges`;
CREATE TABLE IF NOT EXISTS `charges` (
  `charge_ID` int(11) AUTO_INCREMENT PRIMARY KEY,
  `type` varchar(10) DEFAULT NULL,
  `rate` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`charge_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `charges`(`type`,`rate`) VALUES ('MOMO', 1.5), ('BANK', 0.5);

-- --------------------------------------------------------

--
-- Table structure for table `trans_history`
--

DROP TABLE IF EXISTS `trans_history`;
CREATE TABLE IF NOT EXISTS `trans_history` (
  `transID` int(11) AUTO_INCREMENT PRIMARY KEY,
  `type` varchar(7) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `amount` decimal(10,0) DEFAULT NULL,
  `levy_charge` decimal(10,0) DEFAULT NULL,
  `bank_charge` decimal(10,0) DEFAULT NULL,
  `trans_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trans_time` time DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`transID`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `full_name` varchar(65) NOT NULL,
  `dob` date NOT NULL,
  `email_address` varchar(60) UNIQUE NOT NULL,
  `phone_number` varchar(10) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `date_registered` date DEFAULT CURRENT_DATE,
  `gender` char(1) NOT NULL,
) ENGINE=MariaDB DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
