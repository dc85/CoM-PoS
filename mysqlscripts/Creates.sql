-- ----------------------------------------------------------------------
-- MySQL Migration Toolkit
-- SQL Create Script
-- ----------------------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `DB`
  CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `DB`;
-- -------------------------------------
-- Tables

DROP TABLE IF EXISTS `DB`.`tblCustomer`;
CREATE TABLE `DB`.`tblCustomer` (
  `cID` INT(10) NOT NULL AUTO_INCREMENT,
  `cTitle` INT(10) NULL,
  `cFirstN` VARCHAR(50) NULL,
  `cLastN` VARCHAR(50) NULL,
  `cAKA` VARCHAR(50) NULL,
  `cDoB` DATETIME NULL,
  `cCustType` DOUBLE(7, 2) NULL,
  `cExpert` INT(10) NULL,
  `cE1` TINYINT(1) NOT NULL,
  `cE2` TINYINT(1) NOT NULL,
  `cE3` TINYINT(1) NOT NULL,
  `cE4` TINYINT(1) NOT NULL,
  `cE5` TINYINT(1) NOT NULL,
  `cE6` TINYINT(1) NOT NULL,
  `cE7` TINYINT(1) NOT NULL,
  `cE8` TINYINT(1) NOT NULL,
  `cE9` TINYINT(1) NOT NULL,
  `cE10` TINYINT(1) NOT NULL,
  `cCoName` VARCHAR(125) NULL,
  `cUnitB` VARCHAR(5) NULL,
  `cAdrBus` VARCHAR(255) NULL,
  `cCityB` VARCHAR(50) NULL,
  `cProvB` VARCHAR(2) NULL,
  `cZipB` VARCHAR(25) NULL,
  `cUnitH` VARCHAR(5) NULL,
  `cAdrHome` VARCHAR(255) NULL,
  `cCityH` VARCHAR(50) NULL,
  `cProvH` VARCHAR(2) NULL,
  `cZipH` VARCHAR(25) NULL,
  `cUnitS` VARCHAR(5) NULL,
  `cAdrShip` VARCHAR(255) NULL,
  `cCityS` VARCHAR(50) NULL,
  `cProvS` VARCHAR(2) NULL,
  `cZipS` VARCHAR(25) NULL,
  `cpType1` SMALLINT(5) NULL,
  `cPhone1` VARCHAR(25) NULL,
  `cpType2` SMALLINT(5) NULL,
  `cPhone2` VARCHAR(25) NULL,
  `cpType3` SMALLINT(5) NULL,
  `cPhone3` VARCHAR(25) NULL,
  `cpType4` SMALLINT(5) NULL,
  `cPhone4` VARCHAR(25) NULL,
  `cpType5` SMALLINT(5) NULL,
  `cPhone5` VARCHAR(25) NULL,
  `cEmail1` VARCHAR(125) NULL,
  `cEmail2` VARCHAR(125) NULL,
  `cEmail3` VARCHAR(125) NULL,
  `cRIN` VARCHAR(25) NULL,
  `cDL` VARCHAR(255) NULL,
  `cCardNum` VARCHAR(25) NULL,
  `cSPC` SMALLINT(5) NULL,
  `cTax1` DOUBLE(7, 2) NULL,
  `cTax2` DOUBLE(7, 2) NULL,
  `cEcoFee` TINYINT(1) NOT NULL,
  `cExpNum` VARCHAR(15) NULL,
  `cExpDate` DATETIME NULL,
  `cCustRep` INT(10) NULL,
  `cShirtSize` VARCHAR(4) NULL,
  `cPantSize` VARCHAR(10) NULL,
  `cNote` LONGTEXT NULL,
  `cAware` VARCHAR(255) NULL,
  `cCredit` DOUBLE(7, 2) NULL,
  `cCBal` DOUBLE(7, 2) NULL,
  `cBalance` DOUBLE(7, 2) NULL,
  `cModBy` INT(10) NULL,
  `cModDate` DATETIME NULL,
  `cCrtDate` DATETIME NULL,
  `cDelStatus` TINYINT(1) NOT NULL,
  `cPrnPac` TINYINT(1) NOT NULL,
  `cDND` TINYINT(1) NOT NULL,
  PRIMARY KEY (`cID`),
  INDEX `cCustType` (`cCustType`),
  INDEX `cExpert` (`cExpert`),
  INDEX `cExpNum` (`cExpNum`),
  INDEX `cFirstN` (`cFirstN`),
  INDEX `cLastN` (`cLastN`)
)
ENGINE = INNODB;



SET FOREIGN_KEY_CHECKS = 1;

-- ----------------------------------------------------------------------
-- EOF

