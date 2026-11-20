-- Sesión de caja (apertura / cierre) por sucursal
CREATE TABLE IF NOT EXISTS `caja_sesion` (
  `caja_sesion_id` INT(11) NOT NULL AUTO_INCREMENT,
  `branch_id` INT(11) NOT NULL DEFAULT 0,
  `user_open` INT(11) NOT NULL DEFAULT 0,
  `user_close` INT(11) DEFAULT NULL,
  `opened_at` DATETIME NOT NULL,
  `closed_at` DATETIME DEFAULT NULL,
  `monto_apertura` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `monto_esperado` DECIMAL(14,2) DEFAULT NULL,
  `monto_cierre` DECIMAL(14,2) DEFAULT NULL,
  `diferencia` DECIMAL(14,2) DEFAULT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1=abierta, 0=cerrada',
  `notes_open` TEXT NULL,
  `notes_close` TEXT NULL,
  PRIMARY KEY (`caja_sesion_id`),
  KEY `idx_caja_branch_status` (`branch_id`, `status`),
  KEY `idx_caja_user_status` (`branch_id`, `user_open`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Movimientos de caja ligados a la sesión
CREATE TABLE IF NOT EXISTS `caja_movimiento` (
  `caja_movimiento_id` INT(11) NOT NULL AUTO_INCREMENT,
  `caja_sesion_id` INT(11) NOT NULL,
  `branch_id` INT(11) NOT NULL DEFAULT 0,
  `user_id` INT(11) NOT NULL DEFAULT 0,
  `tipo` VARCHAR(20) NOT NULL COMMENT 'ingreso|egreso|info',
  `categoria` VARCHAR(40) NOT NULL COMMENT 'apertura|venta_efectivo|abono_credito|gasto|compra|venta_tarjeta|venta_transferencia|otro',
  `metodo` VARCHAR(40) DEFAULT 'Efectivo',
  `amount` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `referencia` VARCHAR(120) DEFAULT NULL,
  `detalle` TEXT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`caja_movimiento_id`),
  KEY `idx_caja_mov_sesion` (`caja_sesion_id`),
  KEY `idx_caja_mov_branch` (`branch_id`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
