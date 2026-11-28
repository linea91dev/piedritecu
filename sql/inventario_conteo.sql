-- Conteo de inventario (encabezado + detalle). No afecta stock hasta autorización.

CREATE TABLE IF NOT EXISTS `inventario_conteo` (
  `conteo_id` INT(11) NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(40) NOT NULL,
  `branch_id` INT(11) NOT NULL DEFAULT 0,
  `scope_type` VARCHAR(20) NOT NULL DEFAULT 'all',
  `scope_id` INT(11) NULL DEFAULT NULL,
  `scope_label` VARCHAR(191) NULL DEFAULT NULL,
  `notes` TEXT NULL,
  `status` TINYINT(4) NOT NULL DEFAULT 1 COMMENT '1=borrador,2=pendiente,3=aplicado,0=anulado',
  `created_by` INT(11) NOT NULL DEFAULT 0,
  `authorized_by` INT(11) NULL DEFAULT NULL,
  `authorized_at` DATETIME NULL DEFAULT NULL,
  `date` DATE NOT NULL,
  `datetime` DATETIME NOT NULL,
  `num_products` INT(11) NOT NULL DEFAULT 0,
  `diff_count` INT(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`conteo_id`),
  UNIQUE KEY `uk_conteo_code` (`code`),
  KEY `idx_conteo_status` (`status`),
  KEY `idx_conteo_branch` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `inventario_conteo_detalle` (
  `detalle_id` INT(11) NOT NULL AUTO_INCREMENT,
  `conteo_id` INT(11) NOT NULL,
  `products_id` INT(11) NOT NULL,
  `stock_sistema` DECIMAL(14,4) NOT NULL DEFAULT 0,
  `stock_contado` DECIMAL(14,4) NULL DEFAULT NULL,
  `diferencia` DECIMAL(14,4) NOT NULL DEFAULT 0,
  `counted` TINYINT(1) NOT NULL DEFAULT 0,
  `notes` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`detalle_id`),
  KEY `idx_conteo_det_conteo` (`conteo_id`),
  KEY `idx_conteo_det_prod` (`products_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
