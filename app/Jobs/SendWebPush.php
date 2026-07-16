<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\WebPushService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWebPush implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $user;
    protected string $title;
    protected string $body;
    protected ?string $url;

    public function __construct(User $user, string $title, string $body, ?string $url = null)
    {
        $this->user = $user;
        $this->title = $title;
        $this->body = $body;
        $this->url = $url;
    }

    public function handle(WebPushService $service): void
    {
        $service->sendToUser($this->user, $this->title, $this->body, $this->url);
    }
}
