<?php

namespace App\Livewire;

use App\Models\Chat;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

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
        $this->chats = Auth::user()->chats()
            ->whereRaw('json_array_length(messages) > 0')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();
    }

    public function handleChatCreated()
    {
        // Reload chats when a new chat is created
        \Log::info('SidebarChatList: chat-created event received');
        $this->loadUserChats();
    }

    public function handleMessagesUpdated()
    {
        // Reload chats when messages are updated
        \Log::info('SidebarChatList: messages-updated event received');
        $this->loadUserChats();
    }

    public function render()
    {
        return view('livewire.sidebar-chat-list');
    }
}
