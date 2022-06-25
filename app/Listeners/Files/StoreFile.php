<?php

namespace App\Listeners\Files;

use App\Events\Files\FileProcessed;

class StoreFile
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
     * @param  \App\Events\Files\FileProcessed  $event
     * @return void
     */
    public function handle(FileProcessed $event)
    {
        //
    }
}
