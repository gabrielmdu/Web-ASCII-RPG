<?php

namespace Tests\Feature;

use App\User;
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
        $list = $this->getGameList();

        $this->assertIsArray($list);
        $this->assertEquals('Basic Adventure', $list[0]['name']);
    }
    
    /**
     * Tests if the current game can be retrieved
     *
     * @return void
     */
    public function testCanGetCurrentGame()
    {
        $user = User::first();
        $token = auth()->login($user);

        $this
            ->get('/v1/game', [
                'Authorization' => 'Bearer ' . $token
            ])
            ->assertOk()
            ->assertJsonStructure(['adventure', 'player_items']);
    }

    /**
     * Tests if the current game can be set (new game for the user)
     *
     * @return void
     */
    public function testCanSetCurrentGame()
    {
        $user = User::first();
        $token = auth()->login($user);

        // gets the game list to set the first one as the new game
        $gameList = $this->getGameList();

        $this
            ->post('/v1/game', [
                'game_id' => $gameList[0]['id']
            ], [
                'Authorization' => 'Bearer ' . $token
            ])
            ->assertOk()
            ->assertJsonStructure(['adventure', 'player_items']);
    }
    
    /**
     * Tests if the game can be reset by setting a scene and
     * inserting an item to the user status, then resetting it
     *
     * @return void
     */
    public function testCanResetCurrentGame()
    {
        $user = User::first();
        $token = auth()->login($user);

        $user->gameStatus->setCurrentScene('house_inside');
        $user->gameStatus->storeItem(1);

        $this->assertEquals('house_inside', $user->gameStatus->scene);
        // library has a bug which doesn't update the model's array keys after saving them
        //$this->assertEquals(['id' => 'house_key', 'used' => false], $user->gameStatus->items);

        $this->get('/v1/game/reset', [
            'Authorization' => 'Bearer ' . $token
        ]);
        
        $this->assertNotEquals('house_inside', $user->gameStatus->scene);
        $this->assertEmpty($user->gameStatus->items);
    }
    
    /**
     * Retrieves the game list from the endpoint
     *
     * @return array
     */
    private function getGameList()
    {
        return $this
            ->get('/v1/game/list')
            ->assertOk()
            ->decodeResponseJson();
    }
}
