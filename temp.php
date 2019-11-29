<?php

    require __DIR__ . '/vendor/autoload.php';

   // \Ratchet\Client\connect('wss://echo.websocket.org:443')->then(function($conn) {
    \Ratchet\Client\connect('wss://10.214.214.181:8002/api/v2/channels/samsung.remote.control?name=c2Ftc3VuZ2N0bA==&token=33599151')->then(function($conn) {

        $conn->on('message', function($msg) use ($conn) {
            echo "Received: {$msg}\n";
	    $conn->send('{"method": "ms.remote.control", "params": {"Cmd": "Click", "DataOfCmd": "KEY_VOLDOWN", "Option": "false", "TypeOfRemote": "SendRemoteKey"}}');
            $conn->close();
        });

        //$conn->send('{"method": "ms.remote.control", "params": {"Cmd": "Click", "DataOfCmd": "KEY_VOLDOWN", "Option": "false", "TypeOfRemote": "SendRemoteKey"}}');
    }, function ($e) {
        echo "Could not connect: {$e->getMessage()}\n";
    });
?>