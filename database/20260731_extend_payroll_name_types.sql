ALTER TABLE `payroll`
    MODIFY COLUMN `payroll_name` ENUM('Oficial', 'Interna', 'Bono 14', 'Aguinaldo') NOT NULL DEFAULT 'Oficial';
