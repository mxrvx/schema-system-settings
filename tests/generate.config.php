<?php

/**
 * @noinspection PhpUndefinedConstantInspection
 * @psalm-suppress UndefinedConstant
 * @psalm-suppress MixedArgument
 */

declare(strict_types=1);

$path = static function (array $paths, $withLast = true): string {
    return \implode(DIRECTORY_SEPARATOR, $paths) . ($withLast ? DIRECTORY_SEPARATOR : '');
};


foreach (
    [
        'MODX_BASE_URL' => \getenv('BASE_URL'),
        'MODX_URL_SCHEME' => \getenv('URL_SCHEME'),
        'MODX_HTTP_HOST' => \getenv('HOST'),
        'MODX_SITE_URL' => \getenv('URL_SCHEME') . \getenv('HOST') . '/',
        'MODX_MANAGER_URL' => \getenv('URL_SCHEME') . \getenv('HOST') . '/manager/',
        'MODX_ASSETS_URL' => \getenv('URL_SCHEME') . \getenv('HOST') . '/assets/',
        'MODX_CONNECTORS_URL' => \getenv('URL_SCHEME') . \getenv('HOST') . '/connectors/',
        'MODX_CONNECTORS_PATH' => $path([\dirname(__DIR__), 'runtime', 'connectors']),
        'MODX_PROCESSORS_PATH' => $path([\dirname(__DIR__), 'runtime', 'core', 'processors']),
        'MODX_MANAGER_PATH' => $path([\dirname(__DIR__), 'runtime', 'manager']),
        'MODX_ASSETS_PATH' => $path([\dirname(__DIR__), 'runtime', 'assets']),
        'MODX_BASE_PATH' => $path([\dirname(__DIR__), 'runtime']),
        'MODX_CORE_PATH' => $path([\dirname(__DIR__), 'runtime', 'core']),
        'MODX_CONFIG_PATH' => $path([\dirname(__DIR__), 'runtime', 'core', 'config']),
        'MODX_REVOLUTION_PATH' => $path([\dirname(__DIR__), 'runtime', 'core', 'src', 'Revolution']),
        'MODX_VENDOR_PATH' => $path([\dirname(__DIR__), 'runtime', 'core', 'vendor']),
    ] as $k => $v
) {
    \putenv($k . '=' . $v);
    if (!\defined($k)) {
        \define($k, $v);
    }
}

foreach ([MODX_REVOLUTION_PATH, MODX_CONFIG_PATH, MODX_CORE_PATH, MODX_VENDOR_PATH] as $path) {
    if (!\is_dir($path) && !\mkdir($path, 0775, true)) {
        die(\sprintf("Ошибка: Не удалось создать директорию `%s`", $path));
    }
}

$revolutionPath = \rtrim(MODX_REVOLUTION_PATH, DIRECTORY_SEPARATOR);
if (!\is_dir($revolutionPath)) {
    \symlink(
        $path([\dirname(__DIR__), 'vendor', 'modx', 'revolution' . 'core', 'src', 'Revolution'], false),
        $revolutionPath,
    );
}
$vendorPath = \rtrim(MODX_VENDOR_PATH, DIRECTORY_SEPARATOR);
if (!\is_dir($vendorPath)) {
    \symlink($path([\dirname(__DIR__), 'vendor'], false), $vendorPath);
}


\putenv('DB_DSN=' . \getenv('DB_DRIVER') . ':host=' . \getenv('DB_HOST') . ';port=' . \getenv('DB_PORT') . ';dbname=' . \getenv('DB_NAME'));

$configFile = MODX_CONFIG_PATH . 'config.inc.php';

// NOTE create MODX config
$content = <<<'PHP'
<?php
/**
 *  MODX Configuration file
 */
$database_type = '%s';
$database_server = '%s';
$database_user = '%s';
$database_password = '%s';
$database_connection_charset = '%s';
$dbase = '%s';
$table_prefix = '%s';
$database_dsn = '%s';
$config_options = [
'load_deprecated_global_class_aliases' => false,
];
$driver_options = [];

$lastInstallTime = %s;

$site_id = 'modx';
$site_sessionname = '%s';
$https_port = '%s';
$uuid = '%s';

if (!defined('MODX_LOG_LEVEL_FATAL')) {
    define('MODX_LOG_LEVEL_FATAL', 0);
    define('MODX_LOG_LEVEL_ERROR', 1);
    define('MODX_LOG_LEVEL_WARN', 2);
    define('MODX_LOG_LEVEL_INFO', 3);
    define('MODX_LOG_LEVEL_DEBUG', 4);
}
PHP;

$content = \sprintf(
    $content,
    \getenv('DB_DRIVER'),
    \getenv('DB_HOST'),
    \getenv('DB_USERNAME'),
    \getenv('DB_PASSWORD'),
    \getenv('DB_CHARSET'),
    \getenv('DB_NAME'),
    \getenv('DB_PREFIX'),
    \getenv('DB_DSN'),
    \time(),
    'SN66afe38f0d438',
    '443',
    '33317e99-bdb7-4b57-9bdc-040861968ea3',
    \getenv('MODX_CORE_PATH'),
);

\file_put_contents($configFile, $content);
