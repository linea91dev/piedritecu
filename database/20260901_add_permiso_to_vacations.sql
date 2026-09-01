ALTER TABLE `vacations`
  MODIFY COLUMN `type` ENUM('Gozada','Pagada','Permiso') NOT NULL DEFAULT 'Gozada';
