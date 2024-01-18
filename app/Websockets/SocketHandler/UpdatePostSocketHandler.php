<?php

namespace App\Websockets\SocketHandler;

use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;

class UpdatePostSocketHandler extends BaseSocketHandler
{
    public function onMessage(ConnectionInterface $conn, MessageInterface $msg)
    {
        dump('on message');
        // update post
    }
}
