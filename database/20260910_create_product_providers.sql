-- Relación N:N producto ↔ proveedores (solicitud de compra puede filtrar por cualquiera)
CREATE TABLE IF NOT EXISTS `product_providers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `products_id` INT(11) NOT NULL,
  `provider_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_product_provider` (`products_id`, `provider_id`),
  KEY `idx_provider_id` (`provider_id`),
  KEY `idx_products_id` (`products_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Migrar proveedor principal actual
INSERT IGNORE INTO `product_providers` (`products_id`, `provider_id`)
SELECT `products_id`, `provider`
FROM `products`
WHERE `provider` IS NOT NULL
  AND `provider` > 0;
