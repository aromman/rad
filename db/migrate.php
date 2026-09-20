<?php
/**
 * Endpoint de migraciones de base de datos.
 *
 * El pipeline de despliegue (GitHub Actions) lo llama por HTTP despues de
 * subir los archivos via FTP, para aplicar los .sql nuevos que encuentre en
 * este mismo directorio. Requiere el header X-Migrate-Token con el valor
 * definido en MIGRATE_TOKEN (app/models/constantes.php en el servidor).
 */

ini_set('display_errors', '0');
header('Content-Type: application/json');

require_once __DIR__ . '/../app/models/constantes.php';
require_once __DIR__ . '/../app/models/connection.php';

class MigrationRunner extends Connection
{
    public function pdo(): PDO
    {
        return $this->conexion();
    }
}

function respond($status, $payload)
{
    http_response_code($status);
    echo json_encode($payload);
    exit;
}

$providedToken = isset($_SERVER['HTTP_X_MIGRATE_TOKEN']) ? $_SERVER['HTTP_X_MIGRATE_TOKEN'] : '';
if (!defined('MIGRATE_TOKEN') || MIGRATE_TOKEN === '' || !hash_equals(MIGRATE_TOKEN, $providedToken)) {
    respond(403, array('ok' => false, 'error' => 'forbidden'));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, array('ok' => false, 'error' => 'method not allowed'));
}

function splitSqlStatements($sql)
{
    $lines = array_filter(explode("\n", $sql), function ($line) {
        return !preg_match('/^\s*--/', $line);
    });
    $statements = array_map('trim', explode(';', implode("\n", $lines)));
    return array_values(array_filter($statements, function ($stmt) {
        return $stmt !== '';
    }));
}

try {
    $pdo = (new MigrationRunner())->pdo();

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS schema_migrations (
            filename VARCHAR(255) NOT NULL PRIMARY KEY,
            applied_at DATETIME NOT NULL
        )"
    );

    $applied = $pdo->query('SELECT filename FROM schema_migrations')->fetchAll(PDO::FETCH_COLUMN);

    $files = glob(__DIR__ . '/*.sql');
    sort($files);

    $ranNow = array();
    $skipped = array();

    foreach ($files as $file) {
        $filename = basename($file);

        if (in_array($filename, $applied, true)) {
            $skipped[] = $filename;
            continue;
        }

        $statements = splitSqlStatements(file_get_contents($file));

        // Sin transaccion: las sentencias DDL (CREATE TABLE) hacen commit
        // implicito en MySQL, asi que una transaccion no las cubre igual.
        // Los .sql de este directorio deben ser idempotentes (CREATE TABLE
        // IF NOT EXISTS, INSERT ... ON DUPLICATE KEY UPDATE) para poder
        // reintentarse sin riesgo si algo falla a mitad de camino.
        try {
            foreach ($statements as $statement) {
                $pdo->exec($statement);
            }
            $stm = $pdo->prepare('INSERT INTO schema_migrations (filename, applied_at) VALUES (:filename, NOW())');
            $stm->execute(array(':filename' => $filename));
        } catch (PDOException $e) {
            throw new RuntimeException("Fallo al aplicar $filename: " . $e->getMessage());
        }

        $ranNow[] = $filename;
    }

    respond(200, array('ok' => true, 'applied' => $ranNow, 'skipped' => $skipped));
} catch (Throwable $e) {
    error_log(PHP_EOL . '[' . date('d.m.Y h:i:s') . '] migrate.php: ' . $e->getMessage(), 3, 'my-errors.log');
    respond(500, array('ok' => false, 'error' => 'migration failed, see server logs'));
}
