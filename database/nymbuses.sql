-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2018 at 02:49 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nymbuses`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `addressID` int(11) NOT NULL,
  `doorNo` int(11) NOT NULL,
  `streetName` varchar(255) NOT NULL,
  `postCode` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`addressID`, `doorNo`, `streetName`, `postCode`) VALUES
(1, 413, 'Beverley Road', 'HU5 1LX'),
(2, 17, 'Hammersmith', 'HE19 7SJ'),
(3, 17, 'Ogilby Street', 'SE18 5EJ'),
(4, 91, 'Brikama Road', 'BK14 7NW'),
(7, 12, 'Jamaica Street', 'SE14 5RN'),
(8, 17, 'Milne House', 'SE18 5EJ');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(255) NOT NULL,
  `password` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `password`) VALUES
('admin@nym.com', 123);

-- --------------------------------------------------------

--
-- Table structure for table `buses`
--

CREATE TABLE `buses` (
  `BusID` int(11) NOT NULL,
  `NoPassengers` int(11) NOT NULL,
  `BusType` enum('doubleDecker','singleDecker') NOT NULL DEFAULT 'doubleDecker'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `buses`
--

INSERT INTO `buses` (`BusID`, `NoPassengers`, `BusType`) VALUES
(1, 84, 'doubleDecker'),
(2, 49, 'singleDecker'),
(3, 81, 'doubleDecker'),
(4, 47, 'singleDecker'),
(5, 46, 'singleDecker'),
(6, 89, 'doubleDecker'),
(7, 88, 'doubleDecker'),
(9, 44, 'singleDecker');

-- --------------------------------------------------------

--
-- Table structure for table `busroutes`
--

CREATE TABLE `busroutes` (
  `busRouteID` int(11) NOT NULL,
  `busID` int(11) NOT NULL,
  `routeID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `busroutes`
--

INSERT INTO `busroutes` (`busRouteID`, `busID`, `routeID`) VALUES
(14, 1, 10),
(15, 2, 9),
(16, 4, 11);

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `driverID` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `addressID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`driverID`, `title`, `firstName`, `lastName`, `email`, `phone`, `addressID`) VALUES
(1, 'Mr', 'Mohammed', 'Jammeh', 'Mohammedjammeh@yahoo.com', '07506259330', 1),
(2, 'Mr', 'Corey', 'Cross', 'Corey@cross.com', '07928372929', 2),
(3, 'Miss', 'Awa', 'Jammeh', 'awa@jammeh.com', '07839273329', 3),
(4, 'Miss', 'Mariama', 'Ceesay', 'mariama@ceesay.com', '07919246380', 4);

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `routeID` int(11) NOT NULL,
  `routeName` varchar(255) NOT NULL,
  `bridge` enum('no','yes') NOT NULL DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`routeID`, `routeName`, `bridge`) VALUES
(9, 'Beverley Road ', 'yes'),
(10, 'Red Baracks Road', 'no'),
(11, 'Jamaican Street', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `routestops`
--

CREATE TABLE `routestops` (
  `routeStopsID` int(11) NOT NULL,
  `stopID` int(11) NOT NULL,
  `routeID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `routestops`
--

INSERT INTO `routestops` (`routeStopsID`, `stopID`, `routeID`) VALUES
(24, 28, 9),
(25, 29, 9),
(26, 30, 9),
(27, 31, 9),
(28, 32, 9),
(29, 33, 9),
(30, 34, 10),
(31, 35, 10),
(32, 36, 10),
(33, 37, 10),
(34, 38, 10),
(35, 39, 10),
(36, 40, 11),
(37, 41, 11),
(38, 42, 11),
(39, 43, 11),
(40, 44, 11),
(41, 45, 11),
(42, 46, 11),
(43, 47, 11);

-- --------------------------------------------------------

--
-- Table structure for table `routetimes`
--

CREATE TABLE `routetimes` (
  `routeTimeID` int(11) NOT NULL,
  `routeID` int(11) NOT NULL,
  `timeID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `routetimes`
--

INSERT INTO `routetimes` (`routeTimeID`, `routeID`, `timeID`) VALUES
(14, 10, 15),
(15, 9, 16),
(16, 11, 17);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `serviceID` int(11) NOT NULL,
  `serviceName` varchar(255) NOT NULL,
  `routeID` int(11) NOT NULL,
  `driverID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`serviceID`, `serviceName`, `routeID`, `driverID`) VALUES
(16, '380', 10, 3),
(17, '105', 9, 2),
(18, '54', 11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `stops`
--

CREATE TABLE `stops` (
  `stopID` int(11) NOT NULL,
  `stopName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stops`
--

INSERT INTO `stops` (`stopID`, `stopName`) VALUES
(28, 'Beverley Road A'),
(29, 'Beverley Road B'),
(30, 'Beverley Road C'),
(31, 'Beverley Road C1'),
(32, 'Beverley Road B1'),
(33, 'Beverley Road A1'),
(34, 'Red Baracks Road A'),
(35, 'Red Baracks Road B'),
(36, 'Red Baracks Road C'),
(37, 'Red Baracks Road C1'),
(38, 'Red Baracks Road B1'),
(39, 'Red Baracks Road A1'),
(40, 'Jamaican Street A'),
(41, 'Jamaican Street B'),
(42, 'Jamaican Street C'),
(43, 'Jamaican Street D'),
(44, 'Jamaican Street D1'),
(45, 'Jamaican Street C1'),
(46, 'Jamaican Street B1'),
(47, 'Jamaican Street A1');

-- --------------------------------------------------------

--
-- Table structure for table `timess`
--

CREATE TABLE `timess` (
  `timeID` int(11) NOT NULL,
  `journeyStart` time NOT NULL,
  `journeyEnd` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timess`
--

INSERT INTO `timess` (`timeID`, `journeyStart`, `journeyEnd`) VALUES
(15, '14:30:00', '23:30:00'),
(16, '10:00:00', '18:00:00'),
(17, '12:00:00', '23:59:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`addressID`);

--
-- Indexes for table `buses`
--
ALTER TABLE `buses`
  ADD PRIMARY KEY (`BusID`);

--
-- Indexes for table `busroutes`
--
ALTER TABLE `busroutes`
  ADD PRIMARY KEY (`busRouteID`),
  ADD KEY `busID` (`busID`),
  ADD KEY `routeID` (`routeID`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`driverID`),
  ADD KEY `addressID` (`addressID`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`routeID`);

--
-- Indexes for table `routestops`
--
ALTER TABLE `routestops`
  ADD PRIMARY KEY (`routeStopsID`),
  ADD KEY `stopID` (`stopID`),
  ADD KEY `routeID` (`routeID`);

--
-- Indexes for table `routetimes`
--
ALTER TABLE `routetimes`
  ADD PRIMARY KEY (`routeTimeID`),
  ADD KEY `routeID` (`routeID`),
  ADD KEY `timeID` (`timeID`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`serviceID`),
  ADD KEY `routeID` (`routeID`),
  ADD KEY `driverID` (`driverID`);

--
-- Indexes for table `stops`
--
ALTER TABLE `stops`
  ADD PRIMARY KEY (`stopID`);

--
-- Indexes for table `timess`
--
ALTER TABLE `timess`
  ADD PRIMARY KEY (`timeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `addressID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `buses`
--
ALTER TABLE `buses`
  MODIFY `BusID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `busroutes`
--
ALTER TABLE `busroutes`
  MODIFY `busRouteID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `driverID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `routeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `routestops`
--
ALTER TABLE `routestops`
  MODIFY `routeStopsID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `routetimes`
--
ALTER TABLE `routetimes`
  MODIFY `routeTimeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `serviceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `stops`
--
ALTER TABLE `stops`
  MODIFY `stopID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `timess`
--
ALTER TABLE `timess`
  MODIFY `timeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `busroutes`
--
ALTER TABLE `busroutes`
  ADD CONSTRAINT `busroutes_ibfk_1` FOREIGN KEY (`busID`) REFERENCES `buses` (`BusID`),
  ADD CONSTRAINT `busroutes_ibfk_2` FOREIGN KEY (`routeID`) REFERENCES `routes` (`routeID`);

--
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_ibfk_1` FOREIGN KEY (`addressID`) REFERENCES `addresses` (`addressID`);

--
-- Constraints for table `routestops`
--
ALTER TABLE `routestops`
  ADD CONSTRAINT `routestops_ibfk_1` FOREIGN KEY (`stopID`) REFERENCES `stops` (`stopID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `routestops_ibfk_2` FOREIGN KEY (`routeID`) REFERENCES `routes` (`routeID`) ON UPDATE CASCADE;

--
-- Constraints for table `routetimes`
--
ALTER TABLE `routetimes`
  ADD CONSTRAINT `routetimes_ibfk_1` FOREIGN KEY (`routeID`) REFERENCES `routes` (`routeID`),
  ADD CONSTRAINT `routetimes_ibfk_2` FOREIGN KEY (`timeID`) REFERENCES `timess` (`timeID`);

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`routeID`) REFERENCES `routes` (`routeID`),
  ADD CONSTRAINT `services_ibfk_2` FOREIGN KEY (`driverID`) REFERENCES `drivers` (`driverID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
