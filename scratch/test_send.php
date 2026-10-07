<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$account = \App\Models\SocialAccount::where('account_id', '1034255806442438')->first();
if (!$account) {
    echo "No account found\n";
    exit;
}

$meta = new \App\Services\Meta\MetaService();
$latestConv = \App\Models\Conversation::latest()->first();
$psid = $latestConv ? $latestConv->customer_id : '28499360789672279';

echo "Sending message to PSID: $psid ...\n";
$res = $meta->sendText($account->access_token, $psid, 'Hello from Atomation AI Test!');
echo json_encode($res, JSON_PRETTY_PRINT);
echo "\n";
