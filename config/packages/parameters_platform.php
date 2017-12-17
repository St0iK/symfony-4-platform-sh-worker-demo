<?php
$relationships = getenv("PLATFORM_RELATIONSHIPS");
if (!$relationships) {
    return;
}
$relationships = json_decode(base64_decode($relationships), true);
foreach ($relationships['database'] as $endpoint) {
    if (empty($endpoint['query']['is_master'])) {
        continue;
    }
    $container->setParameter('database_driver', 'pdo_' . $endpoint['scheme']);
    $container->setParameter('database_host', $endpoint['host']);
    $container->setParameter('database_port', $endpoint['port']);
    $container->setParameter('database_name', $endpoint['path']);
    $container->setParameter('database_user', $endpoint['username']);
    $container->setParameter('database_password', $endpoint['password']);
    $container->setParameter('database_path', '');
}



if (!empty($relationships['applicationcache'][0])) {
    $container->setParameter('redis_host', $relationships['applicationcache'][0]['host']);
    $container->setParameter('redis_port', $relationships['applicationcache'][0]['port']);
    $container->setParameter('ENQUEUE_DSN', 'redis://'. $relationships['applicationcache'][0]['host'].':'.$relationships['applicationcache'][0]['port']);

}

$variables = getenv("PLATFORM_VARIABLES");

if ($variables) {

    $variables = json_decode(base64_decode($variables), true);

    foreach ($variables as $key => $value) {

        $container->setParameter($key, $value);
    }
}

# Store session into an accessible directory.
ini_set('session.save_path', __DIR__.'/../var/sessions' );