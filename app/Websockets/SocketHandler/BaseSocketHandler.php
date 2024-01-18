<?php

namespace App\Websockets\SocketHandler;

use BeyondCode\LaravelWebSockets\Apps\App;
use BeyondCode\LaravelWebSockets\QueryParameters;
use BeyondCode\LaravelWebSockets\WebSockets\Exceptions\UnknownAppKey;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;

abstract class BaseSocketHandler implements MessageComponentInterface
{
    /**
     * @inheritDoc
     */
    function onOpen(ConnectionInterface $conn)
    {
        dump('on opened');
        // authenticate user
        $this->verifyAppKey($conn);
    }

    /**
     * @inheritDoc
     */
    function onClose(ConnectionInterface $conn)
    {
        dump('closed');
    }

    /**
     * @inheritDoc
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        dump($e);
        dump('onerror');
    }

    /**
     * @param ConnectionInterface $connection
     * @return $this
     * @throws UnknownAppKey
     */
    protected function verifyAppKey(ConnectionInterface $connection)
    {
        $appKey = QueryParameters::create($connection->httpRequest)->get('appKey');
        if (! $app = App::findByKey($appKey)) {
            throw new UnknownAppKey($appKey);
        }
        $connection->app = $app;
        return $this;
    }
}
