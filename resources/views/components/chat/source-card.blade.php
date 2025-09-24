@props(['data'])

<div class="mt-4 p-3 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg">
    <div class="flex items-center space-x-3">
        <!-- Source Icon -->
        <div class="w-10 h-10 bg-zinc-200 dark:bg-zinc-700 rounded-lg flex items-center justify-center flex-shrink-0">
            @if(isset($data['thumbnail']) && $data['thumbnail'])
                <img src="{{ $data['thumbnail'] }}" alt="{{ $data['title'] ?? 'Source' }}" class="w-full h-full object-cover rounded-lg">
            @else
                <flux:icon name="document-text" class="w-5 h-5 text-zinc-600 dark:text-zinc-400" />
            @endif
        </div>
        
        <!-- Source Info -->
        <div class="flex-1 min-w-0">
            <h5 class="text-sm font-medium text-zinc-900 dark:text-white mb-1">
                {{ $data['title'] ?? 'Source' }}
            </h5>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                @if(isset($data['linkType']) && $data['linkType'] === 'review')
                    Casino Review
                @else
                    Knowledge Base
                @endif
            </p>
        </div>
        
        <!-- Link Button -->
        @if(isset($data['url']) && $data['url'])
            <a href="{{ $data['url'] }}" 
               target="_blank" 
               rel="noopener noreferrer"
               class="flex items-center space-x-1 px-2 py-1 text-xs text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-700 rounded transition-colors">
                <span>Read</span>
                <flux:icon name="arrow-top-right-on-square" class="w-3 h-3" />
            </a>
        @endif
    </div>
</div>
