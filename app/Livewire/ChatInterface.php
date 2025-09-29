<?php

namespace App\Livewire;

use App\Models\Chat;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ChatInterface extends Component
{
    public $messages = [];
    public $threadId;
    public $currentChat;
    public $chats = [];

    public function mount($threadId = null)
    {
        if ($threadId) {
            // Load specific chat with caching
            $this->currentChat = $this->getCachedChat($threadId);
            
            if ($this->currentChat) {
                $this->threadId = $threadId;
                $this->messages = $this->getCachedMessages($threadId);
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

    public function loadChatHistory()
    {
        if ($this->currentChat) {
            $this->messages = $this->getCachedMessages($this->currentChat->thread_id);
        }
    }

    public function saveChatHistory()
    {
        if ($this->currentChat) {
            $this->currentChat->updateMessages($this->messages);
            $this->invalidateChatCache($this->currentChat->thread_id);
        } else if ($this->threadId) {
            // Try to find existing chat by threadId
            $this->currentChat = $this->getCachedChat($this->threadId);
            
            if ($this->currentChat) {
                $this->currentChat->updateMessages($this->messages);
                $this->invalidateChatCache($this->threadId);
            }
        }
        
        // Clear user chats cache to refresh sidebar
        $this->invalidateUserChatsCache();
        
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

    public function clearLandingPageQuery()
    {
        if (session('landing_page_query')) {
            session()->forget('landing_page_query');
        }
    }

    public function render()
    {
        $landingPageQuery = session('landing_page_query');
        
        return view('livewire.chat-interface', [
            'threadId' => $this->threadId,
            'landingPageQuery' => $landingPageQuery
        ]);
    }

    /**
     * Get cached chat data
     */
    private function getCachedChat($threadId)
    {
        $cacheKey = "chat_{$threadId}";
        
        return Cache::remember($cacheKey, 600, function () use ($threadId) { // 10 minutes cache
            return Chat::where('thread_id', $threadId)
                ->where('user_id', Auth::id())
                ->first();
        });
    }

    /**
     * Get cached messages for a chat
     */
    private function getCachedMessages($threadId)
    {
        $cacheKey = "chat_messages_{$threadId}";
        
        return Cache::remember($cacheKey, 600, function () use ($threadId) { // 10 minutes cache
            $chat = Chat::where('thread_id', $threadId)
                ->where('user_id', Auth::id())
                ->first();
            
            return $chat ? ($chat->messages ?? []) : [];
        });
    }

    /**
     * Invalidate chat cache when messages are updated
     */
    private function invalidateChatCache($threadId)
    {
        Cache::forget("chat_{$threadId}");
        Cache::forget("chat_messages_{$threadId}");
    }

    /**
     * Invalidate user chats cache when chat list changes
     */
    private function invalidateUserChatsCache()
    {
        $userId = Auth::id();
        Cache::forget("user_chats_{$userId}");
    }
}
