<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServerRequest;
use App\Models\Server;
use Exception;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $servers = Server::simplePaginate(10);
            return response()->json(['data' => $servers]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServerRequest $request)
    {
        try {
            $server = Server::create($request->all());
            return response()->json(['data' => $server]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $server = Server::find($id);
            return response()->json(['data' => $server]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $server = Server::find($id);
            $server->update($request->all());
            return response()->json(['message' => 'Success']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
    }
}
