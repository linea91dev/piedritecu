ALTER TABLE `admin`
    ADD COLUMN `account_number` VARCHAR(50) NULL AFTER `emergency_phone`,
    ADD COLUMN `bank_reference` VARCHAR(100) NULL AFTER `account_number`;
