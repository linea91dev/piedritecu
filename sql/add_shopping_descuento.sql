-- Descuento general por factura en compras
ALTER TABLE `shopping`
  ADD COLUMN IF NOT EXISTS `descuento` DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER `total`;
