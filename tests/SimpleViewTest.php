<?php


use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SimpleViewTest extends TestCase
{
    public function testGuestHome()
    {
        $this
            ->visit('/home')
            ->see('Home')
            ->see('Publisher');
    }

    // public function testCustomer()
    // {
    //     $user = factory(User::class)->create();
    //     $this->be($user);
    //     $this->actingAs($user)
    //         ->visit('/home')
    //         ->see('Order')
    //         ->dontSee('Admin Page');
    //
    //     $user->forceDelete();
    // }

    // public function testAdmin()
    // {
    //     $user = factory(User::class)->create(['level' => 'admin']);
    //     $this
    //         ->visit('/home')
    //         ->see('Admin Page');
    //
    //     $user->forceDelete();
    // }
}
