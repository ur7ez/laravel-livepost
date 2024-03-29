<?php

namespace App\Repositories;

use App\Events\Models\Comment\CommentCreated;
use App\Events\Models\Comment\CommentDeleted;
use App\Events\Models\Comment\CommentUpdated;
use App\Exceptions\GeneralJsonException;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class CommentRepository extends BaseRepository
{
    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $created = Comment::query()->create([
                'body' => data_get($attributes, 'body'),
                'user_id' => data_get($attributes, 'user_id'),
                'post_id' => data_get($attributes, 'post_id'),
            ]);
            throw_if(!$created, GeneralJsonException::class, 'Failed to create comment');
            event(new CommentCreated($created));
            return $created;
        });
    }

    public function update($comment, array $attributes, bool $notify = false)
    {
        return DB::transaction(function () use ($comment, $attributes, $notify) {
            $updated = $comment->update([
                'body' => data_get($attributes, 'body', $comment->body),
                'user_id' => data_get($attributes, 'user_id', $comment->user_id),
                'post_id' => data_get($attributes, 'post_id', $comment->post_id),
            ]);

            throw_if(!$updated, GeneralJsonException::class, 'Failed to update comment');
            if ($notify) {
                event(new CommentUpdated($comment));
            }
            return $comment;
        });
    }

    public function forceDelete($comment)
    {
        return DB::transaction(function () use ($comment) {
            $deleted = $comment->forceDelete();
            throw_if(!$deleted, GeneralJsonException::class, 'Can not delete comment');
            event(new CommentDeleted($comment));
            return $deleted;
        });
    }
}
