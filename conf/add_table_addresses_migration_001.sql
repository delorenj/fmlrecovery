CREATE TABLE `epc`.`addresses` (
  `id` INT  NOT NULL DEFAULT NULL AUTO_INCREMENT,
  `user_id` INT  NOT NULL,
  `nickname` VARCHAR(40)  NOT NULL,
  `companyName` VARCHAR(100)  DEFAULT NULL,
  `streetLines` VARCHAR(200)  NOT NULL,
  `city` VARCHAR(100)  NOT NULL,
  `stateOrProvinceCode` VARCHAR(100)  NOT NULL,
  `postalCode` INTEGER(5)  NOT NULL,
  `phoneNumber` VARCHAR(10)  NOT NULL,
  PRIMARY KEY (`id`)
)
ENGINE = MyISAM;
 `phoneNumber` VARCHAR(10)  NOT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
)
ENGINE = MyISAM;
