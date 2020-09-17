
-- -----------------------------------------------------
-- Table membership.member_statuses
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS membership.member_statuses (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  description VARCHAR(255) CHARACTER SET utf8 NOT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL;


-- -----------------------------------------------------
-- Table membership.member_tiers
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS membership.member_tiers (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  description VARCHAR(255) CHARACTER SET utf8 NOT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL)
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table membership.payment_providers
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS membership.payment_providers (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  description VARCHAR(255) CHARACTER SET utf8 NOT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table membership.members
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS membership.members (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) CHARACTER SET utf8 NOT NULL,
  email VARCHAR(255) CHARACTER SET utf8 NOT NULL UNIQUE,
  rfid VARCHAR(50) CHARACTER SET utf8 NOT NULL UNIQUE,
  expire_date DATE NOT NULL,
  member_status_id INT(10) UNSIGNED NOT NULL,
  member_tier_id INT(10) UNSIGNED NOT NULL,
  payment_provider_id INT(10) UNSIGNED NOT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  
  INDEX members_member_status_id_foreign (member_status_id ASC),
  INDEX members_member_tier_id_foreign (member_tier_id ASC),
  INDEX members_payment_provider_id_foreign (payment_provider_id ASC),
  CONSTRAINT members_member_status_id_foreign
    FOREIGN KEY (member_status_id)
    REFERENCES membership.member_statuses (id),
  CONSTRAINT members_member_tier_id_foreign
    FOREIGN KEY (member_tier_id)
    REFERENCES membership.member_tiers (id),
  CONSTRAINT members_payment_provider_id_foreign
    FOREIGN KEY (payment_provider_id)
    REFERENCES membership.payment_providers (id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table membership.resources
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS membership.resources (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  description VARCHAR(255) CHARACTER SET utf8 NOT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  network_address VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL,
  api_key VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table membership.member_resource
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS membership.member_resource (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  expire_date DATE NULL DEFAULT NULL,
  member_id INT(10) UNSIGNED NOT NULL,
  resource_id INT(10) UNSIGNED NOT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX member_resource_member_id_foreign (member_id ASC),
  INDEX member_resource_resource_id_foreign (resource_id ASC),
  CONSTRAINT member_resource_member_id_foreign
    FOREIGN KEY (member_id)
    REFERENCES membership.members (id),
  CONSTRAINT member_resource_resource_id_foreign
    FOREIGN KEY (resource_id)
    REFERENCES membership.resources (id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;




-- -----------------------------------------------------
-- Table membership.password_resets
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS membership.password_resets (
  email VARCHAR(255) CHARACTER SET utf8 NOT NULL,
  token VARCHAR(255) CHARACTER SET utf8 NOT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  INDEX password_resets_email_index (email ASC),
  INDEX password_resets_token_index (token ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table membership.users
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS membership.users (
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(255) CHARACTER SET utf8 NOT NULL,
  email VARCHAR(255) CHARACTER SET utf8 NOT NULL,
  password VARCHAR(255) CHARACTER SET utf8 NOT NULL,
  remember_token VARCHAR(100) CHARACTER SET utf8 NULL DEFAULT NULL,
  created_at TIMESTAMP NULL DEFAULT NULL,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX users_email_unique (email ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

USE membership ;

-- -----------------------------------------------------
-- View membership.member_table
-- -----------------------------------------------------
Create View IF NOT EXISTS membership.member_table_v
As
Select m.rfid as hash, m.name, m.email, m.expire_date as expiration_date, r.id as resource_id, r.description as resource
from members m
inner join  member_resource mr
on m.id = mr.member_id
inner join resources r
on mr.resource_id = r.id;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_reserved_at_index` (`queue`,`reserved_at`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

