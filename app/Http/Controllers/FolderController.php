<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Jobs\DeleteFile;
use App\Models\DeleteFileQueue;
use App\Models\Folder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            if ($user->folders->count() < 1) {
                $folder = $user->folders()->create(['name' => 'root']);
                return response()->json(['data' => $folder]);
            }
            return response()->json(['data' => $user->folders()->with('folders', 'files')->first()]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $folders = $user->folders;
            if ($folders->where('name', $request->name)->first()) {
                return response()->json(['message' => 'Folder name existe'], 422);
            }
            $folder = $user->folders()->create($request->all());
            return response()->json(['data' => $folder]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $folder = Folder::with('folders', 'files')->find($id);
            return response()->json(['data' => $folder]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = Auth::user();
            $folder = $user->folders()->find($id);
            $folder->update(['name' => $request->name]);
            return response()->json(['message' => 'Success']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = Auth::user();
            $folder = $user->folders()->find($id);
            $folder->files()->with('server')->each(function ($file) {
                $fileQueue = DeleteFileQueue::create([
                    'file_id' => $file->id,
                    'server_id' => $file->server->id,
                    'user_id' => 1
                ]);
                DeleteFile::dispatch($fileQueue);
            });
            $folder->folders()->delete();
            $folder->delete();
            return response()->json(['message' => 'Success']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
