<?php

use App\User;
use App\Game;
use App\Order;
use App\UserBalance;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderApiTest extends TestCase
{
    protected $user;
    protected $game;

    protected function setUp()
    {
        parent::setUp();
        $this->game = factory(Game::class)->create(['price' => 100]);
    }

    protected function tearDown()
    {

        if($this->user) {

            if ($this->user->orders()) {
                $this->user->orders()->forceDelete();
            }

            if ($this->user->userbalance()) {
                $this->user->userbalance()->forceDelete();
            }

            $this->user->forceDelete();
        }

        $this->game->forceDelete();

        parent::tearDown();
    }

    public function testReadAllOrderGuest()
    {
        $response = $this->get(
            '/api/order/',
            ['Accept' => 'application/json']
        );

        $response->assertResponseStatus(401);
    }

    public function testReadAllOrderCustomer()
    {
        $this->user = factory(User::class)->create();

        $response = $this
            ->actingAs($this->user, 'api')
            ->get(
                '/api/order/',
                ['Accept' => 'application/json']
            );
        $response->assertResponseStatus(200);
    }

    public function testReadAllOrderAdmin()
    {
        $this->user = factory(User::class)->create(['level'=>'admin']);

        $response = $this
            ->actingAs($this->user, 'api')
            ->get(
                '/api/order/',
                ['Accept' => 'application/json']
            );

        $response->assertResponseStatus(200);
    }

    public function testCreateOrderGuest()
    {
        $this->user = factory(User::class)->create();

        $response = $this->post(
            '/api/order',
            [
                'user_id' => $this->user->id,
                'game_id' => $this->game->id,
            ],
            ['Accept' => 'application/json']
        );

        $response->assertResponseStatus(401);
    }

    public function testCreateOrderCustomerNotDeposit()
    {
        $this->user = factory(User::class)->create();

        $response = $this
            ->actingAs($this->user, 'api')
            ->post(
                '/api/order',
                [
                    'user_id' => $this->user->id,
                    'game_id' => $this->game->id,
                ],
                ['Accept' => 'application/json']
            );

        $response
            ->assertResponseStatus(500)
            ->seeJson(['message'=>'Insufficient balance!']);
    }

    public function testCreateOrderCustomerInsufficientBalance()
    {
        $this->user = factory(User::class)->create();

        $this->user
            ->userbalance()
            ->save(factory(UserBalance::class)->make(['balance'=>10]));

        $response = $this
            ->actingAs($this->user, 'api')
            ->post(
                '/api/order',
                [
                    'user_id' => $this->user->id,
                    'game_id' => $this->game->id,
                ],
                ['Accept' => 'application/json']
            );

        $response
            ->assertResponseStatus(500)
            ->seeJson(['message'=>'Insufficient balance!']);
    }

    public function testCreateOrderCustomerSufficientBalance()
    {
        $this->user = factory(User::class)->create();

        $this->user
            ->userbalance()
            ->save(factory(UserBalance::class)->make(['balance'=>2000]));

        $response = $this
            ->actingAs($this->user, 'api')
            ->post(
                '/api/order',
                [
                    'user_id' => $this->user->id,
                    'game_id' => $this->game->id,
                ],
                ['Accept' => 'application/json']
            );

        $response
            ->assertResponseStatus(201)
            ->seeJson([
                'user_id' => $this->user->id,
                'balance' => $this->user->userbalance->balance,
            ]);
    }

    public function testCreateOrderCustomerNotDepositAnotherUser()
    {
        $this->user = factory(User::class)->create();
        $target_user = factory(User::class)->create();

        $response = $this
            ->actingAs($this->user, 'api')
            ->post(
                '/api/order',
                [
                    'user_id' => $target_user->id,
                    'game_id' => $this->game->id,
                ],
                ['Accept' => 'application/json']
            );

        $response->assertResponseStatus(403);


        if ($target_user->userbalance()) {
            $target_user->userbalance()->forceDelete();
            if ($target_user->orders()) {
                $target_user->orders()->forceDelete();
            }
            $target_user->forceDelete();
        }
    }

    public function testCreateOrderCustomerInsufficientBalanceAnotherUser()
    {
        $this->user = factory(User::class)->create();
        $target_user = factory(User::class)->create();

        $this->user
            ->userbalance()
            ->save(factory(UserBalance::class)->make(['balance'=>10]));

        $response = $this
            ->actingAs($this->user, 'api')
            ->post(
                '/api/order',
                [
                    'user_id' => $target_user->id,
                    'game_id' => $this->game->id,
                ],
                ['Accept' => 'application/json']
            );

        $response->assertResponseStatus(403);

        if ($target_user->userbalance()) {
            $target_user->userbalance()->forceDelete();
            if ($target_user->orders()) {
                $target_user->orders()->forceDelete();
            }
            $target_user->forceDelete();
        }
    }

    public function testCreateOrderCustomerSufficientBalanceAnotherUser()
    {
        $this->user = factory(User::class)->create();
        $target_user = factory(User::class)->create();

        $this->user
            ->userbalance()
            ->save(factory(UserBalance::class)->make(['balance'=>2000]));

        $response = $this
            ->actingAs($this->user, 'api')
            ->post(
                '/api/order',
                [
                    'user_id' => $target_user->id,
                    'game_id' => $this->game->id,
                ],
                ['Accept' => 'application/json']
            );

        $response->assertResponseStatus(403);


        if ($target_user->userbalance()) {
            $target_user->userbalance()->forceDelete();
            if ($target_user->orders()) {
                $target_user->orders()->forceDelete();
            }
            $target_user->forceDelete();
        }
    }
}
