<?php

namespace App\Livewire;

use App\Models\Chat;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SidebarChatList extends Component
{
    public $chats = [];

    protected $listeners = [
        'chat-created' => 'handleChatCreated',
        'messages-updated' => 'handleMessagesUpdated',
    ];

    public function mount()
    {
        $this->loadUserChats();
    }

    public function loadUserChats()
    {
        $userId = Auth::id();
        $cacheKey = "user_chats_{$userId}";
        
        $this->chats = Cache::remember($cacheKey, 300, function () use ($userId) { // 5 minutes cache
            return Auth::user()->chats()
                ->whereRaw('json_array_length(messages) > 0')
                ->orderBy('last_message_at', 'desc')
                ->take(10)
                ->get();
        });
    }

    public function handleChatCreated()
    {
        // Clear cache and reload chats when a new chat is created
        $this->invalidateUserChatsCache();
        $this->loadUserChats();
    }

    public function handleMessagesUpdated()
    {
        // Clear cache and reload chats when messages are updated
        $this->invalidateUserChatsCache();
        $this->loadUserChats();
    }

    /**
     * Invalidate user chats cache
     */
    private function invalidateUserChatsCache()
    {
        $userId = Auth::id();
        Cache::forget("user_chats_{$userId}");
    }

    public function render()
    {
        return view('livewire.sidebar-chat-list');
    }
}
