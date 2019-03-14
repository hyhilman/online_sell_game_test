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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->authorize('store', Game::class);

        $gameId = Game::insertGetId($request->all());
        $newData = Game::findOrFail($gameId);
        return response()->json($newData, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Game::withCount('order')->findOrFail($id);
        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $this->authorize('update', Game::class);

        $data = Game::findOrFail($id);
        $data->update($request->all());

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $this->authorize('destroy', Game::class);

        $data = Game::findOrFail($id);

        if ($data->delete()) {

            return response()->json('', 200);

        } else {

            return response()->json(
                ['message' => 'Failed to delete the requested data!'],
                200
            );

        }
    }
}
