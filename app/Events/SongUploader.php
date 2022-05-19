<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SongUploader
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Request $request;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return bool|string
     */
    public function upload(): bool|string
    {
        $title = $this->request->input('title');
        $file = $this->request->file("src");
        $file_name = $title.'_'.Str::random().'_'.'song'.'.'.$file->getClientOriginalExtension();

        $uploaded_file = $file->move(public_path("/songs/"),$file_name);

        return $uploaded_file ? asset("/songs/".$file_name) : false;
    }
}
