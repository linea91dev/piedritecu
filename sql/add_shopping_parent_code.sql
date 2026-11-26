-- Relación madre/hija para subórdenes de compra por proveedor
ALTER TABLE `shopping`
  ADD COLUMN `parent_code` VARCHAR(40) NULL DEFAULT NULL AFTER `code`,
  ADD COLUMN `allocations_json` TEXT NULL AFTER `products`,
  ADD INDEX `idx_shopping_parent_code` (`parent_code`);
