<x-layouts.app :title="__('Chat')">
    <div wire:key="chat-interface-{{ $threadId ?? 'new' }}">
        @livewire('chat-interface', ['threadId' => $threadId ?? null])
    </div>
</x-layouts.app>
