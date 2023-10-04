<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'path',
        'size',
        'folder_id',
        'server_id',
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
