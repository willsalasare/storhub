<?php

namespace App\Helpers;

use App\Models\Server;
use Illuminate\Support\Facades\Storage;

class Helper
{
    public static function buildStorage(Server $server)
    {
        return Storage::build([
            'driver' => 'ftp',
            'host' => $server->host,
            'username' => $server->username,
            'password' => $server->password,
            // 'port' => $server->port,
        ]);
    }
}
