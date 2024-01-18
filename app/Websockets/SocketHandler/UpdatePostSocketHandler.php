<?php

namespace App\Websockets\SocketHandler;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Repositories\PostRepository;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;

class UpdatePostSocketHandler extends BaseSocketHandler
{
    public function onMessage(ConnectionInterface $conn, MessageInterface $msg)
    {
        $body = collect(json_decode($msg->getPayload(), true));
        $payload = $body->get('payload');
        $id = $body->get('id');
        // dump($payload, $id);
        // update post
        $post = Post::query()->findOrFail($id);
        $repo = new PostRepository();
        $updated = $repo->update($post, $payload, false);
        $response = (new PostResource($updated))->toJson();
        $conn->send($response);
    }
}
