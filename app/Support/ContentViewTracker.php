<?php

namespace App\Support;

use App\Models\ContentView;
use Illuminate\Http\Request;

class ContentViewTracker
{
    public function record(string $contentType, int $contentId, Request $request): void
    {
        $ipHash = hash_hmac('sha256', $request->ip() ?? 'unknown', (string) config('app.key'));

        ContentView::query()->insertOrIgnore([
            'content_type' => $contentType,
            'content_id' => $contentId,
            'ip_hash' => $ipHash,
            'viewed_on' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
