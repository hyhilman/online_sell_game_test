<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use Auth;
use App\User;
use App\Topup;
use App\Events\Transaction;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TopupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Topup::where('user_id', Auth::guard('api')->user()->id)->paginate(8);
        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::with('userbalance')->findOrFail(Auth::guard('api')->user()->id);
        $topup = new Topup($request->except('_token'));
        $this->authorizeForUser($user, 'store', $topup);

        if ($request->amount == 10 
            || $request->amount == 25 
            || $request->amount == 50
        ) {
            event(new Transaction($user, $topup));
            $user->userbalance->fresh();
            return response()->json($user, 201);
        } else {
            return response()
                ->json(['message' => 'Request Data Invalid!'])
                ->setStatusCode(400);
        }
    }
}
