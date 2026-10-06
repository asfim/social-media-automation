<?php
// Scratch end-to-end test: fake Meta webhook -> conversation/comment -> auto reply (Graph API faked)
use Illuminate\Support\Facades\Http;
use App\Models\SocialAccount;
use App\Models\Faq;

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

Http::fake([
    'graph.facebook.com/*/me/messages' => Http::response(['message_id' => 'm_reply_1'], 200),
    'graph.facebook.com/*/*/comments' => Http::response(['id' => 'c_reply_1'], 200),
    'graph.facebook.com/*/me*' => Http::response(['id' => 'PAGE1', 'name' => 'Test Page'], 200),
    'graph.facebook.com/*' => Http::response(['name' => 'Rahim Ahmed', 'profile_pic' => null], 200),
]);

SocialAccount::updateOrCreate(['account_id' => 'PAGE1'], ['platform' => 'facebook', 'account_name' => 'Test Page', 'access_token' => 'TOKEN', 'is_active' => true]);
Faq::firstOrCreate(['question' => 'What is your website price'], ['answer' => 'Websites start at 20,000 BDT.', 'keywords' => 'price cost website', 'status' => true]);

$msg = ['object' => 'page', 'entry' => [['id' => 'PAGE1', 'messaging' => [['sender' => ['id' => 'USER1'], 'message' => ['mid' => 'm_test_' . uniqid(), 'text' => 'website price koto?']]]]]];
$cmt = ['object' => 'page', 'entry' => [['id' => 'PAGE1', 'changes' => [['field' => 'feed', 'value' => ['item' => 'comment', 'verb' => 'add', 'comment_id' => 'cm_' . uniqid(), 'post_id' => 'PAGE1_POST1', 'message' => 'Website price?', 'from' => ['id' => 'USER2', 'name' => 'Karim']]]]]]];

App\Jobs\ProcessIncomingMessage::dispatchSync($msg, 'facebook');
App\Jobs\ProcessIncomingMessage::dispatchSync($cmt, 'facebook');

echo "Conversations: " . App\Models\Conversation::count() . "\n";
foreach (App\Models\ConversationMessage::latest('id')->take(3)->get() as $m) echo "  [{$m->sender_type}] {$m->message_text}\n";
foreach (App\Models\Comment::latest('id')->take(1)->get() as $c) echo "Comment: {$c->comment_text} => status={$c->reply_status} reply={$c->ai_reply_text}\n";
echo "Leads: " . App\Models\Lead::count() . "\n";

// Cleanup fake data
App\Models\Lead::whereIn('profile_id', ['USER1'])->delete();
App\Models\Comment::where('external_post_id', 'PAGE1_POST1')->delete();
App\Models\Conversation::where('external_conversation_id', 'PAGE1_USER1')->delete();
SocialAccount::where('account_id', 'PAGE1')->delete();
Faq::where('question', 'What is your website price')->delete();
echo "cleaned\n";
