<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$token = 'EAAQHaysUsAoBSkeTyYNSXPsR9zjx29UqbWYO5yOvcOLfmFrAZBQdO4wvfmdFjJ0MuOWxZBJZApIGsuWQVQj9XU5ZBYWfZBuIbEZCe0ZBhMrWXQtjAuLLhZCfxVCZBns67VZCYlFX0llrTjekXW6cE5XPIpl2LDJTqhr0qGahbFsM1whbnbLnu42sXwCJTavXfEuzkZBiM0QKLGZBlPEohSXQqC9898O6ncK5xZC984hPB3KtDzzXeQqAzmHoJVLgnfWLOWTqu9DiZC7dWiMmIqzW5Gp2ZBvvZBOxZC3FxGOJxto4ZD';
$res = Illuminate\Support\Facades\Http::get('https://graph.facebook.com/v21.0/debug_token', ['input_token' => $token, 'access_token' => $token])->json();
echo json_encode($res);
