<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Game;
use App\User;


class GameApiTest extends TestCase
{
    protected $user;
    protected $game;

    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {

        if( $this->user ) {
            $this->user->forceDelete();
        }

        if( $this->game ) {
            $this->game->forceDelete();
        }
        parent::tearDown();
    }

    public function testReadAllGame()
    {
        $response = $this->get(
            '/api/games/',
            ['Accept' => 'application/json']
        );

        $response->assertResponseStatus(200);
    }

    public function testReadSpecifiedGame()
    {
        $this->game = factory(Game::class)->create();

        $response = $this
            ->get(
                '/api/games/'.$this->game->id,
                ['Accept' => 'application/json']
            );

        $response
            ->assertResponseStatus(200)
            ->seeJson([
                'title' => $this->game->title,
                'image_url' => $this->game->image_url,
                'price' => $this->game->price,
                'description' => $this->game->description,
                'publisher' => $this->game->publisher
            ]);
    }

    public function testCreateGameGuest()
    {
        $this->game = factory(Game::class)->make();


        $response = $this
            ->post(
                '/api/games/',
                [
                    'title' => $this->game->title,
                    'release_date' => $this->game->release_date,
                    'image_url' => $this->game->image_url,
                    'price' => $this->game->price,
                    'description' => $this->game->description,
                    'publisher' => $this->game->publisher,
                ],
                ['Accept' => 'application/json']
            );

        $response->assertResponseStatus(401);

    }

    public function testCreateGameCustomer()
    {
        $this->game = factory(Game::class)->make();
        $this->user = factory(User::class)->create();

        $response = $this
            ->actingAs($this->user, 'api')
            ->post(
                '/api/games/',
                [
                    'title' => $this->game->title,
                    'release_date' => $this->game->release_date,
                    'image_url' => $this->game->image_url,
                    'price' => $this->game->price,
                    'description' => $this->game->description,
                    'publisher' => $this->game->publisher,
                ],
                ['Accept' => 'application/json']
            );
        
        $response->assertResponseStatus(403);
    }

    public function testCreateGameAdmin()
    {
        $this->game = factory(Game::class)->make();
        $this->user = factory(User::class)->create(['level'=>'admin']);

        $response = $this
            ->actingAs($this->user, 'api')
            ->post(
                '/api/games/',
                [
                    'title' => $this->game->title,
                    'release_date' => $this->game->release_date,
                    'image_url' => $this->game->image_url,
                    'price' => $this->game->price,
                    'description' => $this->game->description,
                    'publisher' => $this->game->publisher,
                ],
                ['Accept' => 'application/json']
            );

        $response
            ->assertResponseStatus(201)
            ->seeJson([
                'title' => $this->game->title,
                'image_url' => $this->game->image_url,
                'price' => $this->game->price,
                'description' => $this->game->description,
                'publisher' => $this->game->publisher,
            ]);
    }

    public function testUpdateGameGuest()
    {
        $this->game = factory(Game::class)->create();
        $newGame = factory(Game::class)->make();

        $response = $this
            ->put(
                '/api/games/'.$this->game->id,
                [
                    'title' => $newGame->title,
                    'release_date' => $newGame->release_date,
                    'image_url' => $newGame->image_url,
                    'price' => $newGame->price,
                    'description' => $newGame->description,
                    'publisher' => $newGame->publisher,
                ],
                ['Accept' => 'application/json']
            );

        $response->assertResponseStatus(401);
    }

    public function testUpdateGameCustumer()
    {
        $this->game = factory(Game::class)->create();
        $this->user = factory(User::class)->create();
        $newGame = factory(Game::class)->make();

        $response = $this
            ->actingAs($this->user, 'api')
            ->put(
                '/api/games/'.$this->game->id,
                [
                    'title' => $newGame->title,
                    'release_date' => $newGame->release_date,
                    'image_url' => $newGame->image_url,
                    'price' => $newGame->price,
                    'description' => $newGame->description,
                    'publisher' => $newGame->publisher,
                ],
                ['Accept' => 'application/json']
            );

        $response->assertResponseStatus(403);
    }

    public function testUpdateGameAdmin()
    {
        $this->game = factory(Game::class)->create();
        $this->user = factory(User::class)->create(['level'=>'admin']);
        $newGame = factory(Game::class)->make();

        $response = $this
            ->actingAs($this->user, 'api')
            ->put(
                '/api/games/'.$this->game->id,
                [
                    'title' => $newGame->title,
                    'release_date' => $newGame->release_date,
                    'image_url' => $newGame->image_url,
                    'price' => $newGame->price,
                    'description' => $newGame->description,
                    'publisher' => $newGame->publisher,
                ],
                ['Accept' => 'application/json']
            );

        $response
            ->assertResponseStatus(200)
            ->seeJson([
                'id' => $this->game->id,
                'title' => $newGame->title,
                'image_url' => $newGame->image_url,
                'price' => $newGame->price,
                'description' => $newGame->description,
                'publisher' => $newGame->publisher
            ]);
    }

    public function testDeleteGameGuest()
    {
        $this->game = factory(Game::class)->create();

        $response = $this
            ->delete(
                '/api/games/'.$this->game->id,
                [],
                ['Accept' => 'application/json']
            );

        $response->assertResponseStatus(401);
    }

    public function testDeleteGameCustumer()
    {
        $this->game = factory(Game::class)->create();
        $this->user = factory(User::class)->create();

        $response = $this
            ->actingAs($this->user, 'api')
            ->delete(
                '/api/games/'.$this->game->id,
                [],
                ['Accept' => 'application/json']
            );

        $response->assertResponseStatus(403);
    }

    public function testDeleteGameAdmin()
    {
        $this->game = factory(Game::class)->create();
        $this->user = factory(User::class)->create(['level'=>'admin']);

        $response = $this
            ->actingAs($this->user, 'api')
            ->delete(
                '/api/games/'.$this->game->id,
                [],
                ['Accept' => 'application/json']
            );

        $response->assertResponseStatus(204);
    }
}
