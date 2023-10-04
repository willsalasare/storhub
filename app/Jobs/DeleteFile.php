<?php

namespace App\Jobs;

use App\Helpers\Helper;
use App\Models\DeleteFileQueue;
use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public DeleteFileQueue $deleteFileQueue
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $file = File::find($this->deleteFileQueue->file_id);
        // $storage = Helper::buildStorage($file->server);
        // $storage->delete($file->path);
    }
}
