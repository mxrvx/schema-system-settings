<?php

declare(strict_types=1);

\error_reporting(E_ALL | E_STRICT);
\ini_set('display_errors', '1');

require \dirname(__DIR__) . '/vendor/autoload.php';

\putenv('PHPUNIT=1');
\putenv('BASE_URL=/');
\putenv('URL_SCHEME=http://');
\putenv('URL_HOST=localhost');
\putenv('DB_DRIVER=mysql');
\putenv('DB_HOST=' . \str_contains(PHP_OS, 'WIN') ? 'mariadb' : 'localhost');
\putenv('DB_PORT=3306');
\putenv('DB_NAME=dbunit');
\putenv('DB_USERNAME=dbunit');
\putenv('DB_PASSWORD=dbunit');
\putenv('DB_CHARSET=utf8');
\putenv('DB_PREFIX=');
\putenv('DB_TEST_SQL_FILE=' . __DIR__ . '/mysql.sql');

require 'generate.config.php';
