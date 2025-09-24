@props(['data'])

<div class="mt-4 p-3 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg">
    <div class="flex items-start space-x-2">
        <flux:icon name="code-bracket" class="w-4 h-4 text-zinc-500 mt-0.5 flex-shrink-0" />
        <div class="flex-1 min-w-0">
            <pre class="text-xs text-zinc-700 dark:text-zinc-300 whitespace-pre-wrap overflow-x-auto"><code>{{ json_encode($data, JSON_PRETTY_PRINT) }}</code></pre>
        </div>
    </div>
</div>
