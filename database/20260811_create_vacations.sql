CREATE TABLE IF NOT EXISTS `vacations` (
  `vacation_id` INT(11) NOT NULL AUTO_INCREMENT,
  `employee_id` INT(11) NOT NULL,
  `date_start` DATE NOT NULL,
  `date_end` DATE NOT NULL,
  `days` INT(11) NOT NULL DEFAULT 0,
  `type` ENUM('Gozada','Pagada') NOT NULL DEFAULT 'Gozada',
  `amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `note` TEXT NULL,
  `responsable` INT(11) NOT NULL,
  `branch_id` INT(11) NOT NULL,
  `datetime` DATETIME NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`vacation_id`),
  KEY `employee_id` (`employee_id`),
  KEY `branch_id` (`branch_id`),
  KEY `date_start` (`date_start`),
  KEY `date_end` (`date_end`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
