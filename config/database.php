<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'server' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_SERVER', '127.0.0.1'),
            'port' => env('DB_PORT_SERVER', '3306'),
            'database' => env('DB_DATABASE_SERVER', 'forge'),
            'username' => env('DB_USERNAME_SERVER', 'forge'),
            'password' => env('DB_PASSWORD_SERVER', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'sis_student' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_SECOND', '127.0.0.1'),
            'port' => env('DB_PORT_SECOND', '3306'),
            'database' => env('DB_DATABASE_SECOND', 'forge'),
            'username' => env('DB_USERNAME_SECOND', 'forge'),
            'password' => env('DB_PASSWORD_SECOND', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'sis_courses' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_SIS_COURSES', '127.0.0.1'),
            'port' => env('DB_PORT_SIS_COURSES', '3306'),
            'database' => env('DB_DATABASE_SIS_COURSES', 'forge'),
            'username' => env('DB_USERNAME_SIS_COURSES', 'forge'),
            'password' => env('DB_PASSWORD_SIS_COURSES', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'sis_offerings' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_SIS_OFFERINGS', '127.0.0.1'),
            'port' => env('DB_PORT_SIS_OFFERINGS', '3306'),
            'database' => env('DB_DATABASE_SIS_OFFERINGS', 'forge'),
            'username' => env('DB_USERNAME_SIS_OFFERINGS', 'forge'),
            'password' => env('DB_PASSWORD_SIS_OFFERINGS', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'sis_college' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_SIS_COLLEGE', '127.0.0.1'),
            'port' => env('DB_PORT_SIS_COLLEGE', '3306'),
            'database' => env('DB_DATABASE_SIS_COLLEGE', 'forge'),
            'username' => env('DB_USERNAME_SIS_COLLEGE', 'forge'),
            'password' => env('DB_PASSWORD_SIS_COLLEGE', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'sis_tools' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_SIS_TOOLS', '127.0.0.1'),
            'port' => env('DB_PORT_SIS_TOOLS', '3306'),
            'database' => env('DB_DATABASE_SIS_TOOLS', 'forge'),
            'username' => env('DB_USERNAME_SIS_TOOLS', 'forge'),
            'password' => env('DB_PASSWORD_SIS_TOOLS', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'skyhr' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_SKYHR', '10.5.0.40'),
            'port' => env('DB_PORT_SKYHR', '58378'),
            'database' => env('DB_DATABASE_SKYHR', 'skyhr'),
            'username' => env('DB_USERNAME_SKYHR', 'forge'),
            'password' => env('DB_PASSWORD_SKYHR', 'LNU@hris'),
            // 'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8',
            // 'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            // 'strict' => true,
            // 'engine' => null,
            // 'options' => [
            //     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            //     PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8,
            //     'Encrypt' => false,
            //     'TrustServerCertificate' => false,
            // ],
            // 'encrypt' => env('DB_ENCRYPT', 'yes'),
            'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'true'),
        ],

        // 'cpanel' => [
        //     'driver' => 'mysql',
        //     'url' => env('DATABASE_URL'),
        //     'host' => env('DB_HOST_CPANEL', '127.0.0.1'),
        //     'port' => env('DB_PORT_CPANEL', '3306'),
        //     'database' => env('DB_DATABASE_CPANEL', 'forge'),
        //     'username' => env('DB_USERNAME_CPANEL', 'forge'),
        //     'password' => env('DB_PASSWORD_CPANEL', ''),
        //     'unix_socket' => env('DB_SOCKET', ''),
        //     'charset' => 'utf8mb4',
        //     'collation' => 'utf8mb4_unicode_ci',
        //     'prefix' => '',
        //     'prefix_indexes' => true,
        //     'strict' => true,
        //     'engine' => null,
        //     'options' => extension_loaded('pdo_mysql') ? array_filter([
        //         PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        //     ]) : [],
        // ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            // 'encrypt' => env('DB_ENCRYPT', 'yes'),
            // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
