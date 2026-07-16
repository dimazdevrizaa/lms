<?php

namespace App\Services;

use App\Models\User;
use App\Models\PushSubscription;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Illuminate\Support\Facades\Log;

class WebPushService
{
    protected ?WebPush $webPush = null;

    public function __construct()
    {
        $publicKey = env('VAPID_PUBLIC_KEY');
        $privateKey = env('VAPID_PRIVATE_KEY');
        $subject = env('VAPID_SUBJECT', 'mailto:admin@lms-sma15.sch.id');

        if ($publicKey && $privateKey) {
            $clientOptions = [];
            if (app()->environment('local')) {
                $clientOptions['verify'] = false;
            }

            $this->webPush = new WebPush([
                'VAPID' => [
                    'subject' => $subject,
                    'publicKey' => $publicKey,
                    'privateKey' => $privateKey,
                ],
            ], [], 30, $clientOptions);
        }
    }

    public function sendToUser(User $user, string $title, string $body, ?string $url = null): void
    {
        if (!$this->webPush) {
            Log::warning('WebPushService not configured: VAPID keys missing.');
            return;
        }

        $subscriptions = PushSubscription::where('user_id', $user->id)->get();

        if ($subscriptions->isEmpty()) {
            return;
        }

        foreach ($subscriptions as $sub) {
            $webPushSub = Subscription::create([
                'endpoint' => $sub->endpoint,
                'publicKey' => $sub->p256dh_key,
                'authToken' => $sub->auth_token,
            ]);

            $payload = json_encode([
                'title' => $title,
                'body' => $body,
                'url' => $url,
            ]);

            $this->webPush->queueNotification($webPushSub, $payload);
        }

        try {
            foreach ($this->webPush->flush() as $report) {
                $endpoint = $report->getEndpoint();
                if (!$report->isSuccess()) {
                    Log::info("Web push failed for endpoint: {$endpoint}. Removing expired subscription.");
                    PushSubscription::where('endpoint', $endpoint)->delete();
                }
            }
        } catch (\Exception $e) {
            Log::error('Error flushing web push notifications: ' . $e->getMessage());
        }
    }
}
