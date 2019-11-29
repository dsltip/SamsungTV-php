<?php

$tvip = '10.214.214.181';
$tvtoken = '33599151';
$tvkey = 'KEY_VOLUP';

    require __DIR__ . '/vendor/autoload.php';

    $loop = \React\EventLoop\Factory::create();

    $reactConnector = new \React\Socket\Connector($loop, array(
    'tls' => array(
        'verify_peer' => false,
        'verify_peer_name' => false
    ),
));

    $connector = new \Ratchet\Client\Connector($loop, $reactConnector);

    $connector('wss://' . $tvip .':8002/api/v2/channels/samsung.remote.control?name=c2Ftc3VuZ2N0bA==&token=' . $tvtoken)
    ->then(function(Ratchet\Client\WebSocket $conn) {
        $conn->on('message', function(\Ratchet\RFC6455\Messaging\MessageInterface $msg) use ($conn) {
            echo "Received: {$msg}\n";
	    $conn->send('{"method": "ms.remote.control", "params": {"Cmd": "Click", "DataOfCmd": "' . $tvkey .'", "Option": "false", "TypeOfRemote": "SendRemoteKey"}}');
            $conn->close();
        });

        $conn->on('close', function($code = null, $reason = null) {
            echo "Connection closed ({$code} - {$reason})\n";
        });

        //$conn->send('Hello World!');
    }, function(\Exception $e) use ($loop) {
        echo "Could not connect: {$e->getMessage()}\n";
        $loop->stop();
    });

    $loop->run();
?>