<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GameTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Tests if the game list can be retrieved 
     *
     * @return void
     */
    public function testCanGetGameList()
    {
        $list = $this
            ->get('/v1/game/list')
            ->assertOk()
            ->decodeResponseJson();

        $this->assertIsArray($list);
        $this->assertEquals('Basic Adventure', $list[0]['name']);
    }
}
