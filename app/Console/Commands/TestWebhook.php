<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SocialAccount;
use App\Jobs\ProcessIncomingMessage;
use App\Models\Conversation;
use App\Models\Comment;

class TestWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:webhook {type=message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate an incoming Meta webhook (message or comment) for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');

        $account = SocialAccount::first();
        if (!$account) {
            $this->info("Creating a dummy Facebook SocialAccount for testing...");
            $account = SocialAccount::create([
                'platform' => 'facebook',
                'account_name' => 'My Test Page',
                'account_id' => '123456789',
                'access_token' => 'dummy_token',
                'is_active' => true,
            ]);
        }

        if ($type === 'message') {
            $payload = [
                'object' => 'page',
                'entry' => [
                    [
                        'id' => $account->account_id,
                        'time' => time() * 1000,
                        'messaging' => [
                            [
                                'sender' => ['id' => '987654321'],
                                'recipient' => ['id' => $account->account_id],
                                'timestamp' => time() * 1000,
                                'message' => [
                                    'mid' => 'm_' . uniqid(),
                                    'text' => 'Hello! I need some information about your services.',
                                ]
                            ]
                        ]
                    ]
                ]
            ];
            $this->info("Simulating incoming message...");
        } else {
            $payload = [
                'object' => 'page',
                'entry' => [
                    [
                        'id' => $account->account_id,
                        'time' => time(),
                        'changes' => [
                            [
                                'value' => [
                                    'from' => [
                                        'id' => '987654321',
                                        'name' => 'John Doe'
                                    ],
                                    'item' => 'comment',
                                    'post_id' => '123456_7890',
                                    'verb' => 'add',
                                    'created_time' => time(),
                                    'is_hidden' => false,
                                    'message' => 'This is a test comment! How much is it?',
                                    'comment_id' => 'c_' . uniqid()
                                ],
                                'field' => 'feed'
                            ]
                        ]
                    ]
                ]
            ];
            $this->info("Simulating incoming comment...");
        }

        // Normally handled by WebhookController -> MetaService -> ProcessIncomingMessage
        ProcessIncomingMessage::dispatchSync($payload, 'facebook');
        
        $this->info("Done! Check your /inbox/messenger or /inbox/comments page.");
        
        if ($type === 'message') {
            $conv = Conversation::count();
            $this->info("Total Conversations now: $conv");
        } else {
            $comm = Comment::count();
            $this->info("Total Comments now: $comm");
        }
    }
}
