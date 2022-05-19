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

class FileUploader
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected Request $request;

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
        $title = $this->request->input("title");
        $file = $this->request->file("img");
        $file_name = $title."_".Str::random().'.'.$file->getClientOriginalExtension();
        $uploaded = $file->move(public_path("/files/"),$file_name);

        return $uploaded ? asset("/files/".$file_name) : false;
    }
}
