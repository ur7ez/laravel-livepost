<?php

namespace Tests\Unit;

use App\Exceptions\GeneralJsonException;
use App\Models\Post;
use App\Repositories\PostRepository;
use Tests\TestCase;

class PostRepositoryTest extends TestCase
{
    /**
     * Unit test for create method.
     */
    public function test_create(): void
    {
        // 1. Define the goal:
        // test if create() will actually create a record in DB

        // 2. Replicate the env / restriction
        /** @var PostRepository $repository */
        $repository = $this->app->make(PostRepository::class);

        // 3. Define the source of truth
        $payload = [
            'title' => 'some test title',
            'body' => [],
        ];

        // 4. Compare the result
        $result = $repository->create($payload);
        $this->assertSame($payload['title'], $result->title, 'Post created does not have the same title');
    }

    /**
     * Unit test for update method.
     */
    public function test_update(): void
    {
        // Goal: make sure we can update a post using the update method

        // env
        /** @var PostRepository $repository */
        $repository = $this->app->make(PostRepository::class);
        /** @var PostRepository $dummyPost */
        $dummyPost = Post::factory(1)->create()->first();

        // source of truth
        $payload = [
            'title' => 'abc123',
        ];

        // compare
        $updated = $repository->update($dummyPost, $payload);
        $this->assertSame($payload['title'], $updated->title, 'Post updated does not have the same title');
    }

    public function test_delete_will_throw_exception_when_delete_post_that_doesnt_exist()
    {
        // env
        /** @var PostRepository $repository */
        $repository = $this->app->make(PostRepository::class);
        /** @var PostRepository $dummyPost */
        $dummyPost = Post::factory(1)->make()->first();

        $this->expectException(GeneralJsonException::class);
        $deleted = $repository->forceDelete($dummyPost);
    }

    /**
     * Unit test for delete method.
     */
    public function test_delete(): void
    {
        // Goal: test if forceDelete() is working
        // env
        /** @var PostRepository $repository */
        $repository = $this->app->make(PostRepository::class);
        /** @var PostRepository $dummyPost */
        $dummyPost = Post::factory(1)->create()->first();

        // compare
        $deleted = $repository->forceDelete($dummyPost);

        // verify if it is deleted
        $found = Post::query()->find($dummyPost->id);

        $this->assertNull($found, 'Post is not deleted');
    }
}
