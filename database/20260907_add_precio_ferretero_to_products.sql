ALTER TABLE `products`
    ADD COLUMN `precio_ferretero` DECIMAL(12,2) NOT NULL DEFAULT 0 AFTER `precio_mayorista`;
