<?php

namespace Tests\Feature\Api\V1\Post;

use App\Events\Models\Post\PostCreated;
use App\Events\Models\Post\PostDeleted;
use App\Events\Models\Post\PostUpdated;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    private const EndpointBase = '/api/v1';
    private const EndpointGroup = '/posts';
    protected string $endpoint = self::EndpointBase . self::EndpointGroup;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->make();
        $this->actingAs($user);
    }

    /**
     * A basic feature test example.
     */
    public function test_index(): void
    {
        // load data in DB
        $post = Post::factory(10)->create();
        $postIds = $post->map(fn($post) => $post->id)->toArray();

        // call index endpoint
        $response = $this->json('get', $this->endpoint);

        // assert status
        $response->assertStatus(200);
        // verify records from http response against DB records
        $data = $response->json('data');
        collect($data)->each(fn($post) => $this->assertTrue(in_array($post['id'], $postIds, true)));
    }

    public function test_show(): void
    {
        $dummy = Post::factory()->create();
        // call show endpoint
        $response = $this->json('get', $this->endpoint . '/' . $dummy->id);

        // assert status
        $result = $response->assertStatus(200)->json('data');
        // verify data id
        $this->assertEquals(data_get($result, 'id'), $dummy->id, 'Response ID not the same as model ID');
    }

    public function test_create(): void
    {
        Event::fake();  // stop rising any real events
        $dummy = Post::factory()->make();
        $dummyUser = User::factory()->create();

        // call show endpoint
        $response = $this->json('post', $this->endpoint,
            array_merge($dummy->toArray(), ['user_ids' => [$dummyUser->id]])
        );

        // assert status
        $result = $response->assertStatus(201)->json('data');
        Event::assertDispatched(PostCreated::class);
        // verify data id
        $result = collect($result)->only(array_keys($dummy->getAttributes()));
        $result->each(function ($value, $field) use ($dummy) {
            $this->assertSame(data_get($dummy, $field), $value, 'Fillable is not the same');
        });
    }

    public function test_update(): void
    {
        $dummy = Post::factory()->create();
        $dummy2 = Post::factory()->make();
        Event::fake();
        $fillables = collect((new Post())->getFillable());

        $fillables->each(function ($toUpdate) use ($dummy, $dummy2) {
            // call update endpoint
            $response = $this->json('patch', $this->endpoint . '/' . $dummy->id, [
                $toUpdate => data_get($dummy2, $toUpdate)
            ]);
            $result = $response->assertStatus(200)->json('data');
            Event::assertDispatched(PostUpdated::class);
            $this->assertSame(data_get($dummy2, $toUpdate), data_get($dummy->refresh(), $toUpdate), 'Failed to update model');
        });
    }

    public function test_delete(): void
    {
        Event::fake();
        $dummy = Post::factory()->create();
        $response = $this->json('delete', $this->endpoint . '/' . $dummy->id);

        $result = $response->assertStatus(200);
        Event::assertDispatched(PostDeleted::class);

        $this->expectException(ModelNotFoundException::class);
        Post::query()->findOrFail($dummy->id);
    }
}
