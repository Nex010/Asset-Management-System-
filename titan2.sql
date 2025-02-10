-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Jan 24, 2025 at 11:26 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `titan2`
--

-- --------------------------------------------------------

--
-- Table structure for table `areaprogram`
--

CREATE TABLE `areaprogram` (
  `ApID` int(11) NOT NULL,
  `ApName` varchar(255) DEFAULT NULL,
  `LocationID` int(25) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `areaprogram`
--

INSERT INTO `areaprogram` (`ApID`, `ApName`, `LocationID`, `Description`) VALUES
(1, 'Matibe AP', 12, 'Program focused on community health and education');

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `AssetID` int(255) NOT NULL,
  `AssetName` varchar(255) DEFAULT NULL,
  `SerialNumber` varchar(255) DEFAULT NULL,
  `CatergoryID` int(255) DEFAULT NULL,
  `SubCatergoryID` int(25) DEFAULT NULL,
  `BrandID` int(255) DEFAULT NULL,
  `UserID` int(25) DEFAULT NULL,
  `LocationID` int(255) DEFAULT NULL,
  `Images` blob DEFAULT NULL,
  `st` enum('Available','InUse','UnderMaintainance','Disposed','DueForDisposal') DEFAULT NULL,
  `PuchaseDate` date DEFAULT NULL,
  `PurchaseCost` decimal(10,2) NOT NULL,
  `AssetTag` varchar(25) NOT NULL,
  `Description` text DEFAULT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `APID` int(25) DEFAULT NULL,
  `SupplierID` int(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`AssetID`, `AssetName`, `SerialNumber`, `CatergoryID`, `SubCatergoryID`, `BrandID`, `UserID`, `LocationID`, `Images`, `st`, `PuchaseDate`, `PurchaseCost`, `AssetTag`, `Description`, `OrderID`, `APID`, `SupplierID`) VALUES
(10, 'test', '12345', 1, 1, 1, 1, 1, 0x30, 'Available', '0000-00-00', 200.00, '2345', 'Test', 1, NULL, 1),
(16, 'test', '123456', 1, 1, 1, 1, 1, 0x30, '', '0000-00-00', 5555.00, '2345', 'wwww', 0, NULL, 1),
(20, 'h', '123456', 1, 1, 1, 1, 1, 0x30, 'DueForDisposal', '0000-00-00', 1111.00, '2345', 'te', 11, NULL, 1),
(21, 'Dell', '5CD293948', 1, 1, 1, 1, 1, 0x30, 'Available', '0000-00-00', 1500.00, 'WVZ2345', 'Asset has Accessories', 2345, NULL, 1),
(22, 'HP Probook', '5CG12345', 1, 1, 1, 1, 1, NULL, 'Disposed', '0000-00-00', 2500.00, 'WVZ2345', 'GTD', 12323, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `BrandID` int(11) NOT NULL,
  `BrandName` varchar(25) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Images` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`BrandID`, `BrandName`, `Description`, `Images`) VALUES
(1, 'Apple', 'Innovative consumer electronics company', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CatergoryID` int(11) NOT NULL,
  `CategoryName` varchar(25) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Images` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CatergoryID`, `CategoryName`, `Description`, `Images`) VALUES
(1, 'Furniture', 'Category for all types of furniture', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `LocationID` int(11) NOT NULL,
  `LocationName` varchar(255) DEFAULT NULL,
  `Region` varchar(255) NOT NULL,
  `Description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`LocationID`, `LocationName`, `Region`, `Description`) VALUES
(1, 'Bulawayo', 'Southern Region', 'Suburbs Office');

-- --------------------------------------------------------

--
-- Table structure for table `subcatergory`
--

CREATE TABLE `subcatergory` (
  `SubCatergoryID` int(25) NOT NULL,
  `CategoryID` int(25) DEFAULT NULL,
  `CatergoryName` varchar(255) DEFAULT NULL,
  `CatergoryCode` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcatergory`
--

INSERT INTO `subcatergory` (`SubCatergoryID`, `CategoryID`, `CatergoryName`, `CatergoryCode`) VALUES
(1, 1, 'Mobile Phones', 'MOB123');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `SupplierID` int(11) NOT NULL,
  `SupplierName` varchar(25) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Phone` int(255) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Images` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`SupplierID`, `SupplierName`, `Email`, `Phone`, `Description`, `Images`) VALUES
(1, 'Office Supplies Inc.', 'info@officesupplies.com', 2147483647, 'Provider of office stationery and equipment', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(25) NOT NULL,
  `Username` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Mobile` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `roleID` int(11) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `Images` blob DEFAULT NULL,
  `st` enum('Active','Restricted') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Mobile`, `fullname`, `roleID`, `created_at`, `email`, `Images`, `st`) VALUES
(1, 'bokand_d', 'securepassword', '0987654321', 'Bokang Dube', 2, '2025-01-22', 'jane.doe@example.com', NULL, 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `areaprogram`
--
ALTER TABLE `areaprogram`
  ADD PRIMARY KEY (`ApID`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`AssetID`),
  ADD UNIQUE KEY `CatergoryID` (`CatergoryID`,`SubCatergoryID`,`BrandID`,`UserID`,`OrderID`,`APID`,`SupplierID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `BrandID` (`BrandID`),
  ADD KEY `APID` (`APID`),
  ADD KEY `SupplierID` (`SupplierID`),
  ADD KEY `SubCatergoryID` (`SubCatergoryID`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`BrandID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CatergoryID`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`LocationID`);

--
-- Indexes for table `subcatergory`
--
ALTER TABLE `subcatergory`
  ADD PRIMARY KEY (`SubCatergoryID`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`SupplierID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `areaprogram`
--
ALTER TABLE `areaprogram`
  MODIFY `ApID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `AssetID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `BrandID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CatergoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `LocationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subcatergory`
--
ALTER TABLE `subcatergory`
  MODIFY `SubCatergoryID` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `SupplierID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assets`
--
ALTER TABLE `assets`
  ADD CONSTRAINT `assets_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `assets_ibfk_2` FOREIGN KEY (`BrandID`) REFERENCES `brands` (`BrandID`),
  ADD CONSTRAINT `assets_ibfk_3` FOREIGN KEY (`CatergoryID`) REFERENCES `categories` (`CatergoryID`),
  ADD CONSTRAINT `assets_ibfk_4` FOREIGN KEY (`APID`) REFERENCES `areaprogram` (`ApID`),
  ADD CONSTRAINT `assets_ibfk_5` FOREIGN KEY (`SupplierID`) REFERENCES `suppliers` (`SupplierID`),
  ADD CONSTRAINT `assets_ibfk_6` FOREIGN KEY (`SubCatergoryID`) REFERENCES `subcatergory` (`SubCatergoryID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
