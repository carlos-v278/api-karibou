<?php
// src/YourBundle/Sockets/Chat.php

namespace MyBundle\Sockets;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $rooms;

    public function __construct() {
        $this->rooms = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        // Initialize the user's room here, for example, based on URL parameters
        $query = $conn->httpRequest->getUri()->getQuery();
        parse_str($query, $params);
        $room = isset($params['room']) ? $params['room'] : 'default';

        if (!isset($this->rooms[$room])) {
            $this->rooms[$room] = new \SplObjectStorage;
        }

        $this->rooms[$room]->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $room = $this->getUserRoom($from);

        foreach ($this->rooms[$room] as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $room = $this->getUserRoom($conn);
        $this->rooms[$room]->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    protected function getUserRoom(ConnectionInterface $conn) {
        $query = $conn->httpRequest->getUri()->getQuery();
        parse_str($query, $params);
        return isset($params['room']) ? $params['room'] : 'default';
    }
}