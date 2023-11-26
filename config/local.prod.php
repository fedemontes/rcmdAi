<?php

// Production environment

return function (array $settings): array {
    $settings['db']['database'] = 'rcmdAi';
    $settings['db']['user'] = 'apiuser';
    $settings['db']['password'] = 'macmecmic73!';
    $settings['session'] = [
    'name' => 'sess',
    'lifetime' => 7200,
    'path' => null,
    'domain' => null,
    'secure' => false,
    'httponly' => true,
    'cache_limiter' => 'nocache',
];
    return $settings;
};
