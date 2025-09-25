<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class Chat extends Model
{
    protected $fillable = [
        'user_id',
        'thread_id',
        'title',
        'messages',
        'last_message_at',
    ];

    protected $casts = [
        'messages' => 'array',
        'last_message_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateThreadId(): string
    {
        return 'chat-' . Str::random(32);
    }

    public function getTitleAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        // Generate title from first user message
        $firstUserMessage = collect($this->messages)
            ->firstWhere('role', 'user');

        if ($firstUserMessage) {
            $content = $firstUserMessage['content'] ?? '';
            return Str::limit($content, 50);
        }

        return 'New Chat';
    }

    public function addMessage(array $message): void
    {
        $messages = $this->messages ?? [];
        $messages[] = array_merge($message, [
            'timestamp' => now()->toISOString(),
        ]);
        
        $this->update([
            'messages' => $messages,
            'last_message_at' => now(),
        ]);
    }

    public function updateMessages(array $messages): void
    {
        // Only update last_message_at if there are actually new messages
        $currentMessages = $this->messages ?? [];
        $hasNewMessages = $this->hasNewMessages($currentMessages, $messages);
        
        $updateData = ['messages' => $messages];
        
        // Only update timestamp if there are new messages
        if ($hasNewMessages) {
            $updateData['last_message_at'] = now();
        }
        
        $this->update($updateData);
        
        // Invalidate cache after update
        $this->invalidateCache();
    }
    
    private function hasNewMessages(array $currentMessages, array $newMessages): bool
    {
        // If the count is different, there are definitely new messages
        if (count($newMessages) > count($currentMessages)) {
            return true;
        }
        
        // If counts are the same, check if any message content has changed
        // This handles cases where messages might be reordered or modified
        for ($i = 0; $i < count($newMessages); $i++) {
            $current = $currentMessages[$i] ?? null;
            $new = $newMessages[$i] ?? null;
            
            if (!$current || !$new) {
                return true;
            }
            
            // Compare message content and timestamp
            if ($current['content'] !== $new['content'] || 
                $current['timestamp'] !== $new['timestamp']) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Invalidate cache for this chat
     */
    private function invalidateCache(): void
    {
        Cache::forget("chat_{$this->thread_id}");
        Cache::forget("chat_messages_{$this->thread_id}");
        Cache::forget("user_chats_{$this->user_id}");
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Invalidate cache when chat is created, updated, or deleted
        static::created(function ($chat) {
            $chat->invalidateCache();
        });

        static::updated(function ($chat) {
            $chat->invalidateCache();
        });

        static::deleted(function ($chat) {
            $chat->invalidateCache();
        });
    }
}
