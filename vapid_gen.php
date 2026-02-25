<?php

require __DIR__ . '/vendor/autoload.php';

try {
    $keys = \Minishlink\WebPush\VAPID::createVapidKeys();
} catch (\Throwable $e) {
    echo 'VAPID generation failed via PHP OpenSSL: ' . $e->getMessage() . PHP_EOL;
    echo 'Use this fallback command instead:' . PHP_EOL;
    echo 'npx web-push generate-vapid-keys' . PHP_EOL;
    exit(1);
}

echo 'WEBPUSH_VAPID_PUBLIC_KEY=' . $keys['publicKey'] . PHP_EOL;
echo 'WEBPUSH_VAPID_PRIVATE_KEY=' . $keys['privateKey'] . PHP_EOL;
