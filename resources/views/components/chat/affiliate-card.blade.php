@props(['data'])

<div class="mt-4 p-4 bg-gradient-to-br from-zinc-50 to-zinc-100 dark:from-zinc-800 dark:to-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-start space-x-4">
        <!-- Casino Logo/Thumbnail -->
        <div class="w-16 h-16 bg-gradient-to-br from-accent to-accent/80 rounded-lg flex items-center justify-center flex-shrink-0">
            @if(isset($data['thumbnail']) && $data['thumbnail'])
                <img src="{{ $data['thumbnail'] }}" alt="{{ $data['title'] ?? 'Casino' }}" class="w-full h-full object-cover rounded-lg">
            @else
                <flux:icon name="building-library" class="w-8 h-8 text-white" />
            @endif
        </div>
        
        <!-- Casino Info -->
        <div class="flex-1 min-w-0">
            <h4 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">
                {{ $data['title'] ?? 'Casino Bonus' }}
            </h4>
            
            <!-- Bonus Details -->
            <div class="grid grid-cols-2 gap-3 mb-4">
                @if(isset($data['bonusAmount']) && $data['bonusAmount'])
                    <div class="flex items-center space-x-2">
                        <flux:icon name="gift" class="w-4 h-4 text-accent" />
                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            {{ $data['bonusAmount'] }}
                        </span>
                    </div>
                @endif
                
                @if(isset($data['freeSpins']) && $data['freeSpins'])
                    <div class="flex items-center space-x-2">
                        <flux:icon name="sparkles" class="w-4 h-4 text-accent" />
                        <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            {{ $data['freeSpins'] }}
                        </span>
                    </div>
                @endif
                
                @if(isset($data['wagering']) && $data['wagering'])
                    <div class="flex items-center space-x-2">
                        <flux:icon name="calculator" class="w-4 h-4 text-zinc-500" />
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $data['wagering'] }} wagering
                        </span>
                    </div>
                @endif
                
                @if(isset($data['withdrawalTime']) && $data['withdrawalTime'])
                    <div class="flex items-center space-x-2">
                        <flux:icon name="clock" class="w-4 h-4 text-zinc-500" />
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $data['withdrawalTime'] }} withdrawal
                        </span>
                    </div>
                @endif
            </div>
            
            <!-- CTA Button -->
            @if(isset($data['url']) && $data['url'])
                <a href="{{ $data['url'] }}" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="flex items-center justify-center space-x-2 px-4 py-2 bg-accent text-accent-foreground rounded-lg hover:bg-accent/90 transition-colors font-medium text-sm w-full sm:w-auto">
                    <span>{{ $data['ctaText'] ?? 'Play Now' }}</span>
                    <flux:icon name="arrow-top-right-on-square" class="w-4 h-4" />
                </a>
            @endif
        </div>
    </div>
</div>

