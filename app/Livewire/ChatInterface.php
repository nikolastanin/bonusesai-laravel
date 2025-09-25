<?php

namespace App\Livewire;

use App\Models\Chat;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChatInterface extends Component
{
    public $messages = [];
    public $threadId;
    public $currentChat;
    public $chats = [];

    public function mount($threadId = null)
    {
        if ($threadId) {
            // Load specific chat
            $this->currentChat = Chat::where('thread_id', $threadId)
                ->where('user_id', Auth::id())
                ->first();
            
            if ($this->currentChat) {
                $this->threadId = $threadId;
                $this->messages = $this->currentChat->messages ?? [];
            } else {
                // Chat not found, redirect to new chat
                return redirect()->route('chat');
            }
        } else {
            // Don't create new chat immediately - wait for first message
            $this->threadId = null;
            $this->messages = [];
        }
        
        $this->loadUserChats();
    }

    public function createNewChat()
    {
        $this->currentChat = Chat::create([
            'user_id' => Auth::id(),
            'thread_id' => Chat::generateThreadId(),
            'messages' => [],
        ]);
        
        $this->threadId = $this->currentChat->thread_id;
        $this->messages = [];
        
        // Dispatch event to notify frontend of new chat
        $this->dispatch('new-chat-created', [
            'threadId' => $this->threadId,
            'title' => $this->currentChat->title
        ]);
    }

    public function loadUserChats()
    {
        $this->chats = Auth::user()->chats()
            ->whereRaw('json_array_length(messages) > 0')
            ->take(10)
            ->get();
    }

    public function loadChatHistory()
    {
        if ($this->currentChat) {
            $this->messages = $this->currentChat->messages ?? [];
        }
    }

    public function saveChatHistory()
    {
        if ($this->currentChat) {
            $this->currentChat->updateMessages($this->messages);
        } else if ($this->threadId) {
            // Try to find existing chat by threadId
            $this->currentChat = Chat::where('thread_id', $this->threadId)
                ->where('user_id', Auth::id())
                ->first();
            
            if ($this->currentChat) {
                $this->currentChat->updateMessages($this->messages);
            }
        }
        
        // Dispatch event to update frontend and sidebar
        $this->dispatch('messages-updated');
    }

    public function handleFirstMessage($userMessage)
    {
        // Create new chat if this is the first message
        if (!$this->currentChat) {
            $this->createNewChat();
        }
        
        // Add the user message
        $this->messages[] = [
            'role' => 'user',
            'content' => $userMessage,
            'timestamp' => now()->toISOString(),
        ];
        
        // Save to database
        $this->saveChatHistory();
        
        // Dispatch global event to update sidebar and frontend (only after first message)
        \Log::info('ChatInterface: Dispatching chat-created event');
        $this->dispatch('chat-created');
        
        // Also dispatch a message-updated event for immediate UI update
        \Log::info('ChatInterface: Dispatching messages-updated event');
        $this->dispatch('messages-updated');
        
        // Update URL to include the threadId
        $this->dispatch('url-updated', [
            'url' => route('chat.thread', ['threadId' => $this->threadId])
        ]);
    }

    public function switchToChat($threadId)
    {
        return redirect()->route('chat', ['threadId' => $threadId]);
    }

    public function startNewChat()
    {
        return redirect()->route('chat');
    }

    public function render()
    {
        return view('livewire.chat-interface', [
            'threadId' => $this->threadId
        ]);
    }
}
