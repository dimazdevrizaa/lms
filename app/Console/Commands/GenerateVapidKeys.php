<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Minishlink\WebPush\VAPID;

class GenerateVapidKeys extends Command
{
    protected $signature = 'vapid:generate';
    protected $description = 'Generate VAPID keys for Web Push Notifications';

    public function handle(): int
    {
        $keys = VAPID::createVapidKeys();

        $publicKey = $keys['publicKey'];
        $privateKey = $keys['privateKey'];

        $this->info('VAPID Public Key: ' . $publicKey);
        $this->info('VAPID Private Key: ' . $privateKey);

        $envFile = base_path('.env');
        if (file_exists($envFile)) {
            $content = file_get_contents($envFile);

            if (str_contains($content, 'VAPID_PUBLIC_KEY=')) {
                $content = preg_replace('/VAPID_PUBLIC_KEY=.*/', 'VAPID_PUBLIC_KEY=' . $publicKey, $content);
                $content = preg_replace('/VAPID_PRIVATE_KEY=.*/', 'VAPID_PRIVATE_KEY=' . $privateKey, $content);
            } else {
                $content .= "\n# VAPID Web Push Keys\n";
                $content .= "VAPID_PUBLIC_KEY=" . $publicKey . "\n";
                $content .= "VAPID_PRIVATE_KEY=" . $privateKey . "\n";
                $content .= "VAPID_SUBJECT=mailto:admin@lms-sma15.sch.id\n";
            }

            file_put_contents($envFile, $content);
            $this->info('Keys written to .env file successfully.');
        } else {
            $this->warn('.env file not found. Please add the keys manually.');
        }

        return 0;
    }
}
