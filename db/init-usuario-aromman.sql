-- Alta del usuario aromman con rol administrador (id_rol = 0).
-- La password ya viene hasheada con password_hash(..., PASSWORD_DEFAULT),
-- que es como login.php la valida (password_verify).
-- No hay UNIQUE en users.username, asi que se guarda con NOT EXISTS
-- para no duplicar el usuario si el script se corre mas de una vez.

INSERT INTO `users` (`username`, `email`, `password`, `id_rol`, `id_empleado`)
SELECT 'aromman', 'aromman@gmail.com', '$2y$10$Jauf/7BE2j0lecZeapHwHeil7Vs7aPDwfJ8p0HG0e2mEC4Qha2qwm', 0, 0
WHERE NOT EXISTS (
    SELECT 1 FROM `users` WHERE `username` = 'aromman'
);
