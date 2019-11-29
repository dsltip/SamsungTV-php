<?php

$tvip = '10.214.214.181';

    require __DIR__ . '/vendor/autoload.php';

    $loop = \React\EventLoop\Factory::create();

    $reactConnector = new \React\Socket\Connector($loop, array(
    'tls' => array(
        'verify_peer' => false,
        'verify_peer_name' => false
    ),
));

    $connector = new \Ratchet\Client\Connector($loop, $reactConnector);

    $connector('wss://'. $tvip.':8002/api/v2/channels/samsung.remote.control?name=c2Ftc3VuZ2N0bA==')
    ->then(function(Ratchet\Client\WebSocket $conn) {
        $conn->on('message', function(\Ratchet\RFC6455\Messaging\MessageInterface $msg) use ($conn) {
            echo "Received: {$msg}\n";
            $conn->close();
        });

        $conn->on('close', function($code = null, $reason = null) {
            echo "Connection closed ({$code} - {$reason})\n";
        });

    }, function(\Exception $e) use ($loop) {
        echo "Could not connect: {$e->getMessage()}\n";
        $loop->stop();
    });

    $loop->run();
?>