<?php

declare(strict_types=1);

// ============================================================================
// Backup automático de KAMJuridica — hosting compartido / cPanel (sin SSH)
//
// Corre desde el cron de cPanel. Ejemplo (diario a las 02:30):
//   30 2 * * * /usr/local/bin/ea-php83 /ruta/al/proyecto/scripts/backup.php >> /ruta/al/proyecto/storage/logs/backup.log 2>&1
//
// Ajusta la ruta del binario de PHP según la versión del hosting
// (ea-php83 para PHP 8.3; consulta "Select PHP Version" en cPanel).
//
// Hace: volcado SQL de MySQL (con PDO, sin depender de mysqldump), copia de
// las imágenes (storage/app/public) y del .env, y rota los respaldos antiguos.
// ============================================================================

$rootDir = dirname(__DIR__); // raíz del proyecto (carpeta backend)

// ----------------------------------------------------------------------------
// 1. Leer credenciales desde el .env de Laravel (evita duplicarlas)
// ----------------------------------------------------------------------------
function loadEnv(string $path): array
{
    $env = [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        $pos = strpos($line, '=');
        if ($pos === false) {
            continue;
        }
        $key = trim(substr($line, 0, $pos));
        $value = trim(substr($line, $pos + 1));
        if (strlen($value) >= 2) {
            $first = $value[0];
            $last = $value[strlen($value) - 1];
            if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                $value = substr($value, 1, -1);
            }
        }
        $env[$key] = $value;
    }
    return $env;
}

$env = loadEnv($rootDir . '/.env');

$dbHost = $env['DB_HOST'] ?? '127.0.0.1';
$dbPort = $env['DB_PORT'] ?? '3306';
$dbName = $env['DB_DATABASE'] ?? 'kamjuridica';
$dbUser = $env['DB_USERNAME'] ?? 'root';
$dbPass = $env['DB_PASSWORD'] ?? '';

$backupDir = $rootDir . '/storage/backups';
$keepDays = 14;
$timestamp = date('Y-m-d-His');
$dest = $backupDir . '/' . $timestamp;

if (!is_dir($dest) && !mkdir($dest, 0755, true) && !is_dir($dest)) {
    fwrite(STDERR, 'No se pudo crear ' . $dest . PHP_EOL);
    exit(1);
}

$log = function (string $msg): void {
    echo '[' . date('Y-m-d H:i:s') . '] ' . $msg . PHP_EOL;
};

$log("Iniciando backup en {$dest}");

// ----------------------------------------------------------------------------
// 2. Volcado SQL de la base de datos (PDO)
// ----------------------------------------------------------------------------
try {
    $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $sql = "-- Backup de {$dbName} generado el {$timestamp}\n";
    $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        $create = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(PDO::FETCH_NUM);
        $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
        $sql .= ($create[1] ?? '') . ";\n\n";

        $rows = $pdo->query("SELECT * FROM `{$table}`");
        while ($row = $rows->fetch(PDO::FETCH_ASSOC)) {
            $columns = array_map(static fn ($c) => "`{$c}`", array_keys($row));
            $values = array_map(
                static fn ($v) => $v === null ? 'NULL' : $pdo->quote((string) $v),
                array_values($row)
            );
            $sql .= 'INSERT INTO `' . $table . '` (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $values) . ");\n";
        }
        $sql .= "\n";
    }

    $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

    $compressed = gzencode($sql, 9);
    $dumpFile = $dest . '/database.sql.gz';
    if ($compressed === false) {
        $dumpFile = $dest . '/database.sql';
        file_put_contents($dumpFile, $sql);
    } else {
        file_put_contents($dumpFile, $compressed);
    }

    $log("Base de datos: {$dumpFile}");
} catch (Throwable $e) {
    $log('ERROR volcando la base de datos: ' . $e->getMessage());
}

// ----------------------------------------------------------------------------
// 3. Copia de imágenes (storage/app/public)
// ----------------------------------------------------------------------------
$publicDir = $rootDir . '/storage/app/public';
if (is_dir($publicDir)) {
    $zipFile = $dest . '/storage-public.zip';
    $zip = new ZipArchive();
    if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($publicDir, FilesystemIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            $localPath = $file->getRealPath();
            $relativePath = 'public/' . substr($localPath, strlen($publicDir) + 1);
            $zip->addFile($localPath, $relativePath);
        }
        $zip->close();
        $log("Imágenes: {$zipFile}");
    } else {
        $log('ERROR creando el zip de imágenes');
    }
} else {
    $log('Sin carpeta storage/app/public (se omite)');
}

// ----------------------------------------------------------------------------
// 4. Copia del .env (configuración sensible)
// ----------------------------------------------------------------------------
if (is_file($rootDir . '/.env')) {
    copy($rootDir . '/.env', $dest . '/.env');
    $log("Configuración: {$dest}/.env");
}

// ----------------------------------------------------------------------------
// 5. Rotación: eliminar respaldos más antiguos que $keepDays
// ----------------------------------------------------------------------------
$threshold = time() - ($keepDays * 86400);
foreach (glob($backupDir . '/*', GLOB_ONLYDIR) ?: [] as $dir) {
    if (filemtime($dir) < $threshold) {
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $f) {
            $f->isDir() ? rmdir($f->getRealPath()) : unlink($f->getRealPath());
        }
        rmdir($dir);
        $log("Rotación: eliminado {$dir}");
    }
}

$log('Backup completado. Respaldos en ' . $backupDir);
