<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require __DIR__ . '/vendor/autoload.php';



class AntrianWebSocket implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        echo "WebSocket Server is running...\n";
    }

    public function onOpen(ConnectionInterface $conn) {
        // Ketika ada koneksi baru, tambahkan ke daftar clients
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Broadcast pesan ke semua clients kecuali pengirim
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // Ketika koneksi ditutup, hapus dari daftar clients
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Jalankan WebSocket server di port 8080
$app = new Ratchet\App('localhost', 8080, '0.0.0.0');
$app->route('/antrian', new AntrianWebSocket, ['*']);
$app->run();
