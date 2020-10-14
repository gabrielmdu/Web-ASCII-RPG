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
        $this
            ->get('/v1/scene', $this->getAuthHeader())
            ->assertOk()
            ->assertJsonFragment(['resource_type' => 'scene'])
            ->assertJsonFragment(['title' => 'The Desert']);
    }

    /**
     * Tests if it's possible to choose an option for 
     * the current scene
     *
     * @return void
     */
    public function testCanChooseSceneOption()
    {
        $this
            ->post('/v1/scene', [
                'option' => 1
            ], $this->getAuthHeader())
            ->assertOk()
            ->assertJsonFragment(['resource_type' => 'scene'])
            ->assertJsonFragment(['title' => 'House']);
    }
}
