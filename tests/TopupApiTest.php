<?php

use App\User;
use App\Game;
use App\Topup;
use App\UserBalance;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TopupApiTest extends TestCase
{
    protected $user;

    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {

        if($this->user) {

            if ($this->user->userbalance()) {
                $this->user->userbalance()->forceDelete();
            }

            if ($this->user->topups()) {
                $this->user->topups()->forceDelete();
            }

            $this->user->forceDelete();
        }

        parent::tearDown();
    }

    public function testReadAllTopupGuest()
    {
        $response = $this->get(
            '/api/topups',
            ['Accept' => 'application/json']
        );

        $response->assertResponseStatus(401);
    }

    public function testReadAllTopupCustomer()
    {
        $this->user = factory(User::class)->create();

        $response = $this
            ->actingAs($this->user, 'api')
            ->get(
                '/api/topups',
                ['Accept' => 'application/json']
            );
        $response->assertResponseStatus(200);
    }

    public function testReadAllTopupAdmin()
    {
        $this->user = factory(User::class)->create(['level'=>'admin']);

        $response = $this
            ->actingAs($this->user, 'api')
            ->get(
                '/api/topups/',
                ['Accept' => 'application/json']
            );

        $response->assertResponseStatus(200);
    }

    public function testCreateTopupGuest()
    {
        $this->user = factory(User::class)->create();

        $response = $this->post(
            '/api/topups',
            [
                'user_id' => $this->user->id,
                'amount' => 50,
            ],
            ['Accept' => 'application/json']
        );

        $response->assertResponseStatus(401);
    }

    public function testCreateTopupCustomerRandomAmount()
    {
        $this->user = factory(User::class)->create();

        $response = $this
            ->actingAs($this->user, 'api')
            ->post(
                '/api/topups',
                [
                    'user_id' => $this->user->id,
                    'amount' => 30,
                ],
                ['Accept' => 'application/json']
            );

        $response
            ->assertResponseStatus(400)
            ->seeJson(['message'=>'Request Data Invalid!']);
    }

    public function testCreateTopupCustomer()
    {
        $this->user = factory(User::class)->create();

        $this->user
            ->userbalance()
            ->save(factory(UserBalance::class)->make(['balance'=>10]));

        $response = $this
            ->actingAs($this->user, 'api')
            ->post(
                '/api/topups',
                [
                    'user_id' => $this->user->id,
                    'amount' => 50,
                ],
                ['Accept' => 'application/json']
            );

        $response
            ->assertResponseStatus(201)
            ->seeJson([
                'user_id' => $this->user->id,
                'balance' => 60,
            ]);
    }

    public function testCreateTopupCustomerRandomAmountAnotherUser()
    {
        $this->user = factory(User::class)->create();
        $target_user = factory(User::class)->create();

        $response = $this
            ->actingAs($this->user, 'api')
            ->post(
                '/api/topups',
                [
                    'user_id' => $target_user->id,
                    'amount' => 30,
                ],
                ['Accept' => 'application/json']
            );

        $response->assertResponseStatus(403);

        $target_user->forceDelete();
    }

    public function testCreateTopupCustomerAnotherUser()
    {
        $this->user = factory(User::class)->create();
        $target_user = factory(User::class)->create();

        $this->user
            ->userbalance()
            ->save(factory(UserBalance::class)->make(['balance'=>10]));

        $response = $this
            ->actingAs($this->user, 'api')
            ->post(
                '/api/topups',
                [
                    'user_id' => $target_user->id,
                    'amount' => 50,
                ],
                ['Accept' => 'application/json']
            );

        $response->assertResponseStatus(403);

        $target_user->forceDelete();
    }

}
