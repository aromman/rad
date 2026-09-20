<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'rad');
define('DB_DRIVER', 'mysql');
define('DB_CHARSET', 'utf8');

// Token para db/migrate.php. En produccion se define un valor propio
// directamente en el constantes.php del servidor (este archivo no se
// sube por FTP) y el mismo valor se guarda en el secret MIGRATE_TOKEN
// de GitHub Actions.
define('MIGRATE_TOKEN', 'local-dev-token');
?>