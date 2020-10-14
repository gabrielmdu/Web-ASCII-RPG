<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\HttpHeaderTrait;
use Tests\TestCase;

class SceneTest extends TestCase
{
    use DatabaseMigrations;
    use HttpHeaderTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Tests if the current scene can be retrieved
     *
     * @return void
     */
    public function testCanGetCurrentScene()
    {
        $user = User::first();
        $token = auth()->login($user);

        $this
            ->get('/v1/scene', $this->getAuthHeader($token))
            ->assertOk()
            ->assertJsonFragment(['resource_type' => 'scene'])
            ->assertJsonFragment(['title' => 'The Desert']);
    }

    public function testCanChooseSceneOption()
    {
        $user = User::first();
        $token = auth()->login($user);

        $this
            ->post('/v1/scene', [
                'option' => 1
            ], $this->getAuthHeader($token))
            ->assertOk()
            ->assertJsonFragment(['resource_type' => 'scene'])
            ->assertJsonFragment(['title' => 'House']);
    }
}
