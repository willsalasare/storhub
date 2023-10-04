<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\File;
use App\Models\Server;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $files = File::simplePaginate(10);
            return response()->json(['data' => $files]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'folder_id' => 'required'
        ]);

        try {
            $user = Auth::user();
            $folder = $user->folders()->findOrFail($request->folder_id);

            $server = Server::findOrFail(1);

            $storage = Helper::buildStorage($server);

            foreach ($request->file('files') as $key => $file) {
                $id = Str::random();
                $path = $storage->put('data', $file);
                $user->files()->create([
                    'id' => $id,
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'folder_id' => $folder->id,
                    'server_id' => $server->id,
                ]);
            }

            // if (!$path) return response()->json(['message' => $path], 500);

            return response()->json(['message' => 'Success']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        // $file = $request->file('file');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = Auth::user();
            $file = $user->files()->with('server')->findOrFail($id);
            $storage = Helper::buildStorage($file->server);
            $deleted = $storage->delete($file->path);
            abort_if(!$deleted, 400, 'Err delete file');
            $file->delete();
            return response()->json(['message' => 'File deleted']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
    public function getShareLink($id)
    {
        $url = URL::temporarySignedRoute(
            'download',
            now()->addHour(2),
            ['id' => $id],
        );
        return response()->json(['url' => $url]);
    }
    public function downloadFile(Request $request)
    {
        abort_if(!$request->hasValidSignature(), 401);

        $file = File::with('server')->findOrFail($request->id);

        $storage = Helper::buildStorage($file->server);

        return $storage->download($file->path);
    }
}
