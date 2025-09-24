<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Chat extends Component
{
    public $messages = [];
    public $threadId;

    public function mount()
    {
        $this->threadId = 'user-' . Auth::id() . '-thread';
        $this->loadChatHistory();
    }

    public function loadChatHistory()
    {
        // Load chat history from database or session
        // For now, we'll start with an empty array
        $this->messages = [];
    }

    public function saveChatHistory()
    {
        // Save chat history to database
        // This is called from the frontend JavaScript
        // You can implement database storage here if needed
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
