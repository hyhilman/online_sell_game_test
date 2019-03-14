<?php

namespace App\Http\Controllers\API;

use Auth;
use Gate;
use App\Game;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class GameController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = Game::withCount('order')->paginate(8);
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
        $game =  new Game($request->except('_token'));
        $this->authorizeForUser(Auth::guard('api')->user(), 'store', $game);
        $game->save();
        return response()->json($game, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $game = Game::withCount('order')->findOrFail($id);
        return response()->json($game, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $game = Game::findOrFail($id);
        $this->authorizeForUser(Auth::guard('api')->user(), 'update', $game);
        $game->update($request->except('_token'));
        $game->fresh();
        return response()->json($game, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $game = Game::findOrFail($id);
        $this->authorizeForUser(Auth::guard('api')->user(), 'destroy', $game);
        if ($game->delete()) {

            return response()->json('', 204);

        } else {

            return response()->json(
                ['message' => 'Failed to delete the requested data!'],
                200
            );

        }
    }
}
