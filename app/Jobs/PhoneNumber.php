<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class PhoneNumber implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $token;
    public $data;
    public function __construct($token, $data)
    {
        $this->token = $token;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = Http::withToken($this->token)->post('notify.eskiz.uz/api/message/sms/send', $this->data);
    }
}
