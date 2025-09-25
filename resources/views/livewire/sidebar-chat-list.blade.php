@if($chats->count() > 0)
    <flux:navlist variant="outline">
        <flux:navlist.group :heading="__('Recent Chats')" class="grid">
            @foreach($chats as $chat)
                <flux:navlist.item 
                    icon="chat-bubble-left-right" 
                    :href="route('chat.thread', ['threadId' => $chat->thread_id])" 
                    :current="request()->route('threadId') === $chat->thread_id" 
                    wire:navigate
                    class="flex items-center justify-between group">
                    <span class="truncate flex-1">{{ $chat->title }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">{{ $chat->last_message_at?->diffForHumans() }}</span>
                </flux:navlist.item>
            @endforeach
        </flux:navlist.group>
    </flux:navlist>
@endif
