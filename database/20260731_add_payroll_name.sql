ALTER TABLE `payroll`
    ADD COLUMN `payroll_name` ENUM('Oficial', 'Interna', 'Bono 14', 'Aguinaldo') NOT NULL DEFAULT 'Oficial' AFTER `payroll_id`;
