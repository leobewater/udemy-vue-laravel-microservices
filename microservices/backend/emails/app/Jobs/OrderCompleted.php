<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class OrderCompleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        Mail::send('influencer.admin', [
            'id' => $this->data['id'],
            'admin_total' => $this->data['admin_total'],
        ], function(Message $message) {
            $message->to('admin@admin.com');
            $message->subject('A new order has been completed');
        });

        Mail::send('influencer.influencer', [
            'code' => $this->data['code'],
            'influencer_total' => $this->data['influencer_total'],
        ], function(Message $message) {
            $message->to($this->data['influencer_email']);
            $message->subject('A new order has been completed');
        });

    }
}
