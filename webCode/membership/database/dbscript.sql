-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema hackrvamembership
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema hackrvamembership
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS 'hackrvamembership' DEFAULT CHARACTER SET latin1 ;
USE 'hackrvamembership' ;

-- -----------------------------------------------------
-- Table 'hackrvamembership'.'member_statuses'
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS 'hackrvamembership'.'member_statuses' (
  'id' INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  'description' VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  'created_at' TIMESTAMP NULL DEFAULT NULL,
  'updated_at' TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY ('id'))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table 'hackrvamembership'.'member_tiers'
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS 'hackrvamembership'.'member_tiers' (
  'id' INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  'description' VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  'created_at' TIMESTAMP NULL DEFAULT NULL,
  'updated_at' TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY ('id'))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table 'hackrvamembership'.'payment_providers'
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS 'hackrvamembership'.'payment_providers' (
  'id' INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  'description' VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  'created_at' TIMESTAMP NULL DEFAULT NULL,
  'updated_at' TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY ('id'))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table 'hackrvamembership'.'members'
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS 'hackrvamembership'.'members' (
  'id' INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  'name' VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  'email' VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  'rfid' VARCHAR(50) CHARACTER SET 'utf8' NOT NULL,
  'expire_date' DATE NOT NULL,
  'member_status_id' INT(10) UNSIGNED NOT NULL,
  'member_tier_id' INT(10) UNSIGNED NOT NULL,
  'payment_provider_id' INT(10) UNSIGNED NOT NULL,
  'created_at' TIMESTAMP NULL DEFAULT NULL,
  'updated_at' TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY ('id'),
  UNIQUE INDEX 'members_email_unique' ('email' ASC),
  UNIQUE INDEX 'members_rfid_unique' ('rfid' ASC),
  INDEX 'members_member_status_id_foreign' ('member_status_id' ASC),
  INDEX 'members_member_tier_id_foreign' ('member_tier_id' ASC),
  INDEX 'members_payment_provider_id_foreign' ('payment_provider_id' ASC),
  CONSTRAINT 'members_member_status_id_foreign'
    FOREIGN KEY ('member_status_id')
    REFERENCES 'hackrvamembership'.'member_statuses' ('id'),
  CONSTRAINT 'members_member_tier_id_foreign'
    FOREIGN KEY ('member_tier_id')
    REFERENCES 'hackrvamembership'.'member_tiers' ('id'),
  CONSTRAINT 'members_payment_provider_id_foreign'
    FOREIGN KEY ('payment_provider_id')
    REFERENCES 'hackrvamembership'.'payment_providers' ('id'))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table 'hackrvamembership'.'resources'
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS 'hackrvamembership'.'resources' (
  'id' INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  'description' VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  'created_at' TIMESTAMP NULL DEFAULT NULL,
  'updated_at' TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY ('id'))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table 'hackrvamembership'.'member_resource'
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS 'hackrvamembership'.'member_resource' (
  'id' INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  'expire_date' DATE NULL DEFAULT NULL,
  'member_id' INT(10) UNSIGNED NOT NULL,
  'resource_id' INT(10) UNSIGNED NOT NULL,
  'created_at' TIMESTAMP NULL DEFAULT NULL,
  'updated_at' TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY ('id'),
  INDEX 'member_resource_member_id_foreign' ('member_id' ASC),
  INDEX 'member_resource_resource_id_foreign' ('resource_id' ASC),
  CONSTRAINT 'member_resource_member_id_foreign'
    FOREIGN KEY ('member_id')
    REFERENCES 'hackrvamembership'.'members' ('id'),
  CONSTRAINT 'member_resource_resource_id_foreign'
    FOREIGN KEY ('resource_id')
    REFERENCES 'hackrvamembership'.'resources' ('id'))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table 'hackrvamembership'.'migrations'
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS 'hackrvamembership'.'migrations' (
  'id' INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  'migration' VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  'batch' INT(11) NOT NULL,
  PRIMARY KEY ('id'))
ENGINE = InnoDB
AUTO_INCREMENT = 39
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table 'hackrvamembership'.'password_resets'
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS 'hackrvamembership'.'password_resets' (
  'email' VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  'token' VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  'created_at' TIMESTAMP NULL DEFAULT NULL,
  INDEX 'password_resets_email_index' ('email' ASC),
  INDEX 'password_resets_token_index' ('token' ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table 'hackrvamembership'.'users'
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS 'hackrvamembership'.'users' (
  'id' INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  'name' VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  'email' VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  'password' VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  'remember_token' VARCHAR(100) CHARACTER SET 'utf8' NULL DEFAULT NULL,
  'created_at' TIMESTAMP NULL DEFAULT NULL,
  'updated_at' TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY ('id'),
  UNIQUE INDEX 'users_email_unique' ('email' ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

USE 'hackrvamembership' ;

-- -----------------------------------------------------
-- View 'hackrvamembership'.'member_table'
-- -----------------------------------------------------
Create View IF NOT EXISTS 'hackrvamembership'.'member_table'
As
Select m.rfid as hash, m.name, m.email, m.expire_date, r.id as resource_id, r.description as resource
from members m
inner join  member_resource mr
on m.id = mr.member_id
inner join resources r
on mr.resource_id = r.id;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
