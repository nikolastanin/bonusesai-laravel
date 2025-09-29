<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LandingController extends Controller
{
    /**
     * Handle the landing page form submission
     */
    public function handleSearch(Request $request)
    {
        $query = $request->input('q');
        
        if (empty($query)) {
            return redirect()->back()->with('error', 'Please enter a search query');
        }
        
        // Check if user is authenticated
        if (Auth::check()) {
            // User is logged in, create chat and redirect
            return $this->createChatAndRedirect($query);
        } else {
            // User is not logged in, save query and redirect to login
            Session::put('pending_query', $query);
            return redirect()->route('login');
        }
    }
    
    /**
     * Handle post-login redirect with saved query
     */
    public function handlePostLogin()
    {
        $pendingQuery = Session::pull('pending_query');
        
        if ($pendingQuery) {
            // Create chat with the saved query
            return $this->createChatAndRedirect($pendingQuery);
        }
        
        // No pending query, redirect to dashboard
        return redirect()->route('dashboard');
    }
    
    /**
     * Create a new chat with the query and redirect to it
     */
    private function createChatAndRedirect(string $query)
    {
        $chat = Chat::create([
            'user_id' => Auth::id(),
            'thread_id' => Chat::generateThreadId(),
            'messages' => [],
        ]);
        
        // Store the query in session to be auto-submitted
        Session::put('landing_page_query', $query);
        
        return redirect()->route('chat.thread', ['threadId' => $chat->thread_id]);
    }
}
