<?php

// Dev environment

return function (array $settings): array {
    // Error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');

    //$settings['error']['display_error_details'] = true;
    $settings['logger']['level'] = \Monolog\Level::Debug;

    // Database
    $settings['db']['database'] = 'rcmdAi';
    $settings['db']['username'] = $_ENV['USER_DATABASE'];
    $settings['db']['password'] = $_ENV['PASSWD_DATABASE'];;


    $settings['session'] = [
    'name' => 'sess',
    'lifetime' => 7200,
    'path' => '/',
    'domain' => null,
    'secure' => true,
    'httponly' => false,
    'cache_limiter' => 'nocache',
     ];

    return $settings;
};
