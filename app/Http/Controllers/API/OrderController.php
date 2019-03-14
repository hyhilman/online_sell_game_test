<?php

namespace App\Http\Controllers\API;

use App\Game;
use App\Order;
use App\User;
use App\Events\Transaction;
use Illuminate\Http\Request;
use Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( Auth::Guard('api')->user()->level === "admin")
        {
            $order = Order::with('game')->paginate(8);
        } else {
            $order = Order::with('game')->where('user_id', Auth::Guard('api')->user()->id)->paginate(8);
        }

        return response()->json($order, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $game = Game::findOrFail($request->game_id);
        $user = User::with('userbalance')->findOrFail(Auth::guard('api')->user()->id);
        $order = new Order($request->except('_token'));

        $this->authorizeForUser($user, 'store', $order);

        if (
            !empty($user->userbalance) &&
            $user->userbalance->balance - $game->price >= 0
        ) {
            event(new Transaction($user, $order));
            $user->userbalance->fresh();
            return response()->json($user, 201);

        } else {
            return response()->json(['message' => 'Insufficient balance!'], 500);
        }

    }
}
