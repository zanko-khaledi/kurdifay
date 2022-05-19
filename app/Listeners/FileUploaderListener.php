<?php

namespace App\Listeners;

use App\Events\FileUploader;

class FileUploaderListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(FileUploader $event)
    {
      return $event->upload();
    }
}
