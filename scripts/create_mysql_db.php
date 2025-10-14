<?php

require __DIR__ . '/../vendor/autoload.php';

// Load .env if present
if (file_exists(__DIR__ . '/../.env')) {
	Dotenv\Dotenv::createImmutable(dirname(__DIR__))->safeLoad();
}

$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_DATABASE') ?: 'laravel';
$user = getenv('DB_USERNAME') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';

try {
	$pdo = new PDO("mysql:host={$host};port={$port};charset=utf8mb4", $user, $pass, [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	]);
	$pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
	echo "Database ensured: {$dbName}\n";
} catch (Throwable $e) {
	// Print a concise error for CI/dev visibility
	fwrite(STDERR, 'Failed to ensure database: ' . $e->getMessage() . "\n");
	exit(1);
}


