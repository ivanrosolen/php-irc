<?php

declare(strict_types=1);

namespace Xuplau\IRC;

use React\EventLoop\Factory;
use React\Socket\Server as ReactServer;
use React\Socket\ConnectionInterface;
use React\Socket\LimitingServer;

class Server
{

    private $ip;
    private $port;
    private $serverName;

    public function __construct( string $ip, string $port, string $serverName)
    {
        $this->ip         = $ip;
        $this->port       = $port;
        $this->serverName = $serverName;
    }

    public function run() : void
    {

        $loop   = Factory::create();
        $server = new ReactServer($loop);
        $server->listen($this->port, $this->ip);

        $clients = array();

        $server->on('connection', function (ConnectionInterface $client) use (&$clients) {
            // keep a list of all connected clients
            $clients []= $client;
            $client->on('close', function() use ($client, &$clients) {
                unset($clients[array_search($client, $clients)]);
            });
            // whenever a new message comes in
            $client->on('data', function ($data) use ($client, &$clients) {
                // remove any non-word characters (just for the demo)
                $data = trim(preg_replace('/[^\w\d \.\,\-\!\?]/u', '', $data));
                // ignore empty messages
                if ($data === '') {
                    return;
                }
                // prefix with client IP and broadcast to all connected clients
                $data = $client->getRemoteAddress() . ': ' . $data . PHP_EOL;
                foreach ($clients as $client) {
                    $client->write($data);
                }
            });
        });

        $server->on('error', 'printf');

        echo 'Welcome to ' . $this->serverName . ' at ' . $server->getPort() . PHP_EOL;

        $loop->run();
    }
}