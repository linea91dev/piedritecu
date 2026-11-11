-- Campos de baja / retiro en perfil de empleado
-- Ejecutar una sola vez en la base de datos del sistema.
ALTER TABLE `admin`
  ADD COLUMN `fecha_baja` DATE NULL DEFAULT NULL AFTER `hiring`,
  ADD COLUMN `motivo_retiro` TEXT NULL AFTER `fecha_baja`;
