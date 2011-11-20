SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `mydb` ;
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`order_book_archive`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`order_book_archive` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`order_book_archive` (
  `id` INT NOT NULL ,
  `from` VARCHAR(20) NULL ,
  `bs` VARCHAR(1) NOT NULL ,
  `shares` INT NULL ,
  `stock` VARCHAR(10) NULL ,
  `price` INT NULL ,
  `twilio` TINYINT(1)  NULL ,
  `timestamp` TIMESTAMP NULL ,
  `parent` INT NULL ,
  `state` VARCHAR(1) NULL ,
  `has_child` TINYINT(1)  NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`order_book_active`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`order_book_active` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`order_book_active` (
  `id` INT NOT NULL ,
  `from` VARCHAR(20) NULL ,
  `bs` VARCHAR(1) NOT NULL ,
  `shares` INT NULL ,
  `stock` VARCHAR(10) NULL ,
  `price` INT NULL ,
  `twilio` TINYINT(1)  NULL ,
  `timestamp` TIMESTAMP NULL ,
  `parent` INT NULL ,
  `state` VARCHAR(1) NULL ,
  `has_child` TINYINT(1)  NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`order_book_pending`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`order_book_pending` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`order_book_pending` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `from` VARCHAR(20) NULL ,
  `bs` VARCHAR(1) NOT NULL ,
  `shares` INT NULL ,
  `stock` VARCHAR(10) NULL ,
  `price` INT NULL ,
  `twilio` TINYINT(1)  NULL ,
  `timestamp` TIMESTAMP NULL ,
  `parent` INT NULL ,
  `state` VARCHAR(1) NULL ,
  `has_child` TINYINT(1)  NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`trade_book`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`trade_book` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`trade_book` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `timestamp` TIMESTAMP NULL ,
  `stock` VARCHAR(10) NULL ,
  `buy_ref` INT NULL ,
  `sell_ref` INT NULL ,
  `price` INT NULL ,
  `amount` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`process_singleton_lock`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`process_singleton_lock` ;

CREATE  TABLE IF NOT EXISTS `mydb`.`process_singleton_lock` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `running` TINYINT(1)  NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
