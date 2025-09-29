<div class="flex h-[calc(100vh-64px)]" 
     x-data="chatInterface()" 
     x-init="init()">
    <!-- Background aesthetic: gradient + blurred blobs -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <!-- Dark mode background -->
        <div class="dark:block hidden">
            <div class="absolute -top-32 -left-40 h-[46rem] w-[46rem] rounded-full bg-blue-600/40 blur-3xl"></div>
            <div class="absolute -bottom-40 -right-40 h-[46rem] w-[46rem] rounded-full bg-green-500/40 blur-3xl"></div>
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(50%_50%_at_50%_0%,rgba(255,255,255,0.10)_0%,rgba(16,23,42,0.0)_60%)]"></div>
        </div>
        <!-- Light mode background -->
        <div class="dark:hidden block">
            <div class="absolute -top-32 -left-40 h-[46rem] w-[46rem] rounded-full bg-blue-200/30 blur-3xl"></div>
            <div class="absolute -bottom-40 -right-40 h-[46rem] w-[46rem] rounded-full bg-green-200/30 blur-3xl"></div>
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(50%_50%_at_50%_0%,rgba(0,0,0,0.02)_0%,rgba(255,255,255,0.0)_60%)]"></div>
        </div>
    </div>

    <!-- Chat Interface -->
    <div class="w-full chat-container flex flex-col h-full relative z-10 overflow-hidden">
        <!-- Chat Header -->
        <div class="flex-shrink-0 md:p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-green-500 rounded-lg flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-sm">AI</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">BonusFinder+ai</h3>
                        <p class="text-sm text-gray-600 dark:text-white/70">Chat your way to the best bonuses</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chat Messages -->
        <div class="flex-1 overflow-y-auto p-2 md:p-4 space-y-3 md:space-y-4 relative min-h-0" id="chat-messages" x-ref="chatMessages">
            <!-- Scroll to Beginning Button (shows during streaming and after) -->
            <div x-show="showScrollToTop" 
                 class="fixed bottom-20 right-4 md:right-6 z-20"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95">
                <button @click="scrollToCurrentMessage()"
                        class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm border border-gray-300 dark:border-slate-600 rounded-full p-3 shadow-lg hover:bg-gray-100/90 dark:hover:bg-slate-700/90 transition-colors group"
                        title="Scroll to beginning of current response"
                        x-ref="scrollToTopButton">
                    <svg class="w-5 h-5 text-gray-600/80 dark:text-white/80 group-hover:text-gray-800 dark:group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                </button>
            </div>
            <template x-for="(message, index) in messages" :key="index">
                <div class="flex flex-col md:flex-row md:items-start space-y-1 md:space-y-0 md:space-x-3" 
                     :class="message.role === 'user' ? 'md:flex-row-reverse md:space-x-reverse' : ''"
                     :id="`message-${index}`">
                    <!-- Avatar -->
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-full flex items-center justify-center flex-shrink-0 shadow-lg self-start md:self-auto"
                         :class="message.role === 'user' ? 'bg-gradient-to-br from-blue-500 to-blue-600 md:ml-auto' : 'bg-gradient-to-br from-green-500 to-green-600'">
                        <span x-show="message.role === 'assistant'" class="text-white font-bold text-xs md:text-sm">AI</span>
                        <span x-show="message.role === 'user'" class="text-white font-bold text-xs md:text-sm">U</span>
                    </div>
                    
                    <!-- Message Content -->
                    <div class="w-full max-w-full md:flex-1 md:max-w-[80%]">
                        <!-- Mobile: Show name above message -->
                        <div class="block md:hidden mb-1">
                            <span class="text-xs font-medium text-gray-500 dark:text-white/60" 
                                  :class="message.role === 'user' ? 'text-right block' : 'text-left'"
                                  x-text="message.role === 'user' ? 'You' : 'AI Assistant'">
                            </span>
                        </div>
                        <div class="rounded-lg p-3 md:p-4 shadow-lg border message-bubble card-hover"
                             :class="message.role === 'user' ? 'bg-gradient-to-br from-blue-500 to-blue-600 text-white border-blue-400/30 dark:bg-gradient-to-br dark:from-blue-500/40 dark:to-blue-600/40 dark:text-white dark:border-blue-400/30' : 'bg-white/95 dark:bg-slate-800/95 text-gray-900 dark:text-white border-gray-200 dark:border-slate-600/50'">
                            <div class="text-sm md:text-base leading-relaxed" 
                                 :class="message.role === 'user' ? 'text-white' : 'text-gray-900 dark:text-white'"
                                 x-html="message.formattedContent || message.content"
                                 :data-message-index="index"></div>
                        </div>
                        
                    </div>
                </div>
            </template>
            
        </div>
        
        <!-- Chat Input -->
        <div class="flex-shrink-0 p-2 md:p-4 border-t border-gray-200 dark:border-slate-700/50">
            <div class="relative rounded-lg border border-gray-300 dark:border-slate-600 bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm p-2 shadow-lg">
                <div class="flex space-x-2 md:space-x-3">
                    <input 
                        type="text" 
                        placeholder="Ask me about bonuses..." 
                        class="flex-1 h-10 md:h-12 px-3 md:px-4 bg-white/80 dark:bg-slate-800/80 border-0 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-white/50 outline-none focus:ring-2 focus:ring-blue-500/40 disabled:opacity-50 disabled:cursor-not-allowed input-modern text-sm md:text-base"
                        x-model="currentInput"
                        @keydown.enter="sendMessage()"
                        :disabled="isStreaming"
                        x-ref="messageInput"
                    />
                    <button 
                        x-show="!isStreaming"
                        class="inline-flex items-center gap-1 md:gap-2 rounded-lg btn-gradient px-3 md:px-5 py-2 md:py-3 font-semibold text-white dark:text-slate-900 shadow-lg shadow-blue-500/20 disabled:opacity-50 disabled:cursor-not-allowed text-sm md:text-base"
                        @click="sendMessage()"
                        :disabled="!currentInput.trim()"
                        title="Send message"
                    >
                        <span class="hidden sm:inline">Send</span>
                        <span class="sm:hidden">→</span>
                        <span class="text-xs hidden md:inline">↵</span>
                    </button>
                    <button 
                        x-show="isStreaming"
                        class="inline-flex items-center gap-1 md:gap-2 rounded-lg bg-gradient-to-r from-red-500 to-red-600 px-3 md:px-5 py-2 md:py-3 font-semibold text-white shadow-lg shadow-red-500/20 transition active:scale-[0.99] text-sm md:text-base"
                        @click="cancelMessage()"
                        title="Stop current request"
                    >
                        <span class="hidden sm:inline">Stop</span>
                        <span class="sm:hidden">✕</span>
                        <span class="text-xs hidden md:inline">✕</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function chatInterface() {
    return {
        messages: @json($messages),
        currentInput: '',
        isStreaming: false,
        showScrollToTop: false,
        hasAutoScrolled: false,
        lastAssistantMessageId: null,
        scrollTimeout: null,
        controller: null,
        
        init() {
            // Load messages from Livewire (database)
            const livewireMessages = @json($messages);
            this.messages = livewireMessages || [];
            
            // Sync with Livewire whenever messages change (but don't auto-save during streaming)
            this.$watch('messages', value => {
                // Update Livewire component
                @this.set('messages', value);
                // Only auto-save if not currently streaming to avoid excessive database calls
                if (!this.isStreaming) {
                    @this.call('saveChatHistory');
                }
            });
            
            
            
            // Focus the input field and scroll to bottom on page load
            this.$nextTick(() => {
                if (this.$refs.messageInput) {
                    this.$refs.messageInput.focus();
                }
                
                // If there are existing messages, scroll to bottom immediately after DOM update
                if (this.messages && this.messages.length > 0) {
                    // Use multiple attempts to ensure scrolling works
                    this.scrollToBottom();
                    // Fallback scroll after a very short delay
                    setTimeout(() => {
                        this.scrollToBottom();
                    }, 10);
                }
            });
            
            // Hide scroll to top button when user manually scrolls (debounced)
            if (this.$refs.chatMessages) {
                this.$refs.chatMessages.addEventListener('scroll', () => {
                    // Clear existing timeout
                    if (this.scrollTimeout) {
                        clearTimeout(this.scrollTimeout);
                    }
                    
                    // Set a new timeout - only hide button if user stops scrolling for 500ms
                    this.scrollTimeout = setTimeout(() => {
                        if (this.showScrollToTop) {
                            this.showScrollToTop = false;
                        }
                    }, 500);
                });
            }
        },
        
        
        formatContent(content, renderJson = false) {
            console.log('formatContent called with:', content, 'renderJson:', renderJson);
            
            // Add <br> tags for line breaks, but handle multiple newlines better
            let formattedContent = content
                .replace(/\n\n+/g, '<br><br>') // Multiple newlines become double <br>
                .replace(/\n/g, '<br>'); // Single newlines become single <br>
            
            // Replace JSON blocks with placeholder markers and extract data
            let jsonSnippets = [];
            let snippetIndex = 0;
            
            // During streaming, show placeholders and replace them as soon as JSON is complete
            if (!renderJson) {
                // First, process complete JSON blocks and render them immediately
                formattedContent = formattedContent.replace(/```json\s*([\s\S]*?)```/g, (match, jsonContent) => {
                    try {
                        const cleanJsonContent = jsonContent.replace(/<br\s*\/?>/gi, '\n').trim();
                        const jsonData = JSON.parse(cleanJsonContent);
                        if (Array.isArray(jsonData)) {
                            jsonData.forEach(item => jsonSnippets.push(item));
                        } else {
                            jsonSnippets.push(jsonData);
                        }
                        // Render the element immediately during streaming
                        return this.renderJsonSnippet(jsonData);
                    } catch (e) {
                        console.warn('Failed to parse JSON snippet during streaming:', jsonContent, e);
                        return '<div class="mt-4 p-4 bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm border border-gray-200 dark:border-slate-700/50 rounded-lg shadow-lg"><div class="flex items-center space-x-3"><div class="parsing-indicator"><div class="parsing-dot"></div><div class="parsing-dot"></div><div class="parsing-dot"></div></div><span class="text-sm text-gray-600 dark:text-white/60">🎲 Rolling the dice and parsing results...</span></div></div>';
                    }
                });
                
                // Then, show placeholders for incomplete JSON blocks (those that start with ```json but don't have closing ```)
                formattedContent = formattedContent.replace(/```json[\s\S]*?(?=```|$)/g, '<div class="mt-4 p-4 bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm border border-gray-200 dark:border-slate-700/50 rounded-lg shadow-lg"><div class="flex items-center space-x-3"><div class="parsing-indicator"><div class="parsing-dot"></div><div class="parsing-dot"></div><div class="parsing-dot"></div></div><span class="text-sm text-gray-600 dark:text-white/60">🎲 Rolling the dice and parsing results...</span></div></div>');
                
                // Hide single backtick JSON blocks
                formattedContent = formattedContent.replace(/`json\s*([\s\S]*?)`/g, '');
            }
            
            // Replace triple backtick JSON blocks
            formattedContent = formattedContent.replace(/```json\s*([\s\S]*?)```/g, (match, jsonContent) => {
                console.log('Found triple backtick JSON:', match);
                try {
                    // Remove <br> tags from JSON content before parsing
                    const cleanJsonContent = jsonContent.replace(/<br\s*\/?>/gi, '\n').trim();
                    const jsonData = JSON.parse(cleanJsonContent);
                    console.log('Parsed JSON data:', jsonData);
                    if (Array.isArray(jsonData)) {
                        jsonData.forEach(item => jsonSnippets.push(item));
                    } else {
                        jsonSnippets.push(jsonData);
                    }
                    
                    if (renderJson) {
                        // Replace with actual rendered HTML
                        return this.renderJsonSnippet(jsonData);
                    } else {
                        // Hide JSON during streaming - return empty string
                        return '';
                    }
                } catch (e) {
                    console.warn('Failed to parse JSON snippet:', jsonContent, e);
                    return '';
                }
            });
            
            // Replace single backtick JSON blocks
            formattedContent = formattedContent.replace(/`json\s*([\s\S]*?)`/g, (match, jsonContent) => {
                console.log('Found single backtick JSON:', match);
                try {
                    // Remove <br> tags from JSON content before parsing
                    const cleanJsonContent = jsonContent.replace(/<br\s*\/?>/gi, '\n').trim();
                    const jsonData = JSON.parse(cleanJsonContent);
                    console.log('Parsed JSON data:', jsonData);
                    if (Array.isArray(jsonData)) {
                        jsonData.forEach(item => jsonSnippets.push(item));
                    } else {
                        jsonSnippets.push(jsonData);
                    }
                    
                    if (renderJson) {
                        // Replace with actual rendered HTML
                        return this.renderJsonSnippet(jsonData);
                    } else {
                        // Hide JSON during streaming - return empty string
                        return '';
                    }
                } catch (e) {
                    console.warn('Failed to parse JSON snippet:', jsonContent, e);
                    return '';
                }
            });
            
            // Convert markdown
            formattedContent = formattedContent
                .replace(/### (.*?)(?=<br>|$)/g, '<h3 class="text-lg font-semibold text-gray-900 dark:text-white mt-4 mb-2">$1</h3>')
                .replace(/## (.*?)(?=<br>|$)/g, '<h2 class="text-xl font-semibold text-gray-900 dark:text-white mt-4 mb-2">$1</h2>')
                .replace(/# (.*?)(?=<br>|$)/g, '<h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-4 mb-3">$1</h1>')
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" rel="noopener noreferrer" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 underline transition-colors">$1</a>')
                // Clean up excessive <br> tags and stray # characters
                .replace(/(<br>\s*){3,}/g, '<br><br>') // Replace 3+ consecutive <br> with just 2
                .replace(/<br>\s*#\s*<br>/g, '<br>') // Remove stray # characters with <br> around them
                .replace(/^#\s*<br>/g, '') // Remove # at start of content
                .replace(/<br>\s*#\s*$/g, ''); // Remove # at end of content
            
            console.log('Final result:', { content: formattedContent, jsonSnippets });
                
            return {
                content: formattedContent,
                jsonSnippets: jsonSnippets
            };
        },
        
        extractJsonSnippets(content) {
            const jsonSnippets = [];
            // Match both ```json and `json formats
            const jsonRegex = /(?:```json\s*([\s\S]*?)```|`json\s*([\s\S]*?)`)/g;
            let match;
            
            while ((match = jsonRegex.exec(content)) !== null) {
                try {
                    // Get the JSON content (either from group 1 or group 2)
                    const jsonContent = (match[1] || match[2]).trim();
                    const jsonData = JSON.parse(jsonContent);
                    
                    // Handle both single objects and arrays
                    if (Array.isArray(jsonData)) {
                        // If it's an array, add each item
                        jsonData.forEach(item => {
                            jsonSnippets.push(item);
                        });
                    } else {
                        // If it's a single object, add it
                        jsonSnippets.push(jsonData);
                    }
                } catch (e) {
                    // If JSON parsing fails, skip this snippet
                    console.warn('Failed to parse JSON snippet:', match[1] || match[2]);
                }
            }
            
            return jsonSnippets;
        },
        
        
        renderJsonSnippet(data) {
            const isAffiliate = data.linkType === 'affiliate';
            const isReview = data.linkType === 'review';
            const isSource = data.source === 'knowledge_base';
            const isSourcesArray = Array.isArray(data) && data.length > 0 && data[0].linkType === 'review';
            
            if (isSourcesArray) {
                // Handle sources array - show as horizontal scrollable items
                return `
                    <div class="mt-4">
                        <div class="flex space-x-3 overflow-x-auto pb-2">
                            ${data.map(source => `
                                <div class="flex-shrink-0 w-48 glass-effect rounded-lg p-4 card-hover shadow-lg">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg">
                                            <span class="text-white font-bold text-sm">📄</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-1 line-clamp-2">${source.title}</h5>
                                            <p class="text-xs text-gray-600 dark:text-white/60 mb-2">${source.source === 'knowledge_base' ? 'Knowledge Base' : 'Review'}</p>
                                            ${source.url ? `<a href="${source.url}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center space-x-1 text-xs text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors">
                                                <span>Read more</span>
                                                <span>↗</span>
                                            </a>` : ''}
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            } else if (isAffiliate) {
                return `
                    <div class="mt-4 p-5 glass-effect rounded-lg card-hover shadow-lg relative">
                        <!-- Pin Button -->
                        <button class="absolute top-1 right-1 w-8 h-8 bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm border border-gray-300 dark:border-slate-600 rounded-full flex items-center justify-center shadow-lg hover:bg-gray-100/90 dark:hover:bg-slate-700/90 transition-all group pin-button z-10"
                                title="Pin casino for later"
                                data-casino-title="${data.title || 'Casino Bonus'}"
                                onclick="pinCasino(this, '${data.title || 'Casino Bonus'}')">
                            <svg class="w-4 h-4 text-gray-600/80 dark:text-white/80 group-hover:text-gray-800 dark:group-hover:text-white transition-colors pin-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                            </svg>
                        </button>
                        
                        <div class="grid grid-cols-1 md:grid-cols-[182px_1fr_250px] gap-4 items-start">
                            <!-- Casino Logo/Icon -->
                            <div class="w-full h-auto md:max-w-[182px] bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg overflow-hidden">
                                <img src="https://www.bonus.ca/cdn-cgi/image/width=448,height=144,format=webp,quality=85/https://media.bonus.ca/images/jackpotcity.png" 
                                     alt="Casino Logo" 
                                     class="w-full h-auto object-cover"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="hidden w-full h-full items-center justify-center">
                                    <span class="text-white font-bold text-xl">🎰</span>
                                </div>
                            </div>
                            
                            <!-- Bonus Details -->
                            <div class="min-w-0">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">${data.title || 'Casino Bonus'}</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-1">
                                    ${data.bonusAmount ? `<div class="text-sm text-gray-800 dark:text-white/80">• ${data.bonusAmount}</div>` : ''}
                                    ${data.freeSpins ? `<div class="text-sm text-gray-800 dark:text-white/80">• ${data.freeSpins}</div>` : ''}
                                    ${data.wagering ? `<div class="text-sm text-gray-600 dark:text-white/60">• ${data.wagering} wagering</div>` : ''}
                                    ${data.withdrawalTime ? `<div class="text-sm text-gray-600 dark:text-white/60">• ${data.withdrawalTime} withdrawal</div>` : ''}
                                </div>
                            </div>
                            
                            <!-- CTA Button -->
                            <div class="flex justify-start md:justify-end w-full">
                                ${data.url ? `<a href="${data.url}" target="_blank" rel="noopener noreferrer" class="flex items-center space-x-2 px-4 h-[50px] bg-gradient-to-r from-green-500 to-green-600 text-slate-900 rounded-lg hover:shadow-lg transition-all font-semibold text-sm shadow-lg shadow-green-500/20 w-full justify-center"><span>${data.ctaText || 'Play Now'}</span><span>↗</span></a>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            } else if (isReview || isSource) {
                return `
                    <div class="mt-4 p-4 glass-effect rounded-lg card-hover shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow-lg">
                                <span class="text-white font-bold text-sm">📄</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-1">${data.title || 'Source'}</h5>
                                <p class="text-xs text-gray-600 dark:text-white/60">${isReview ? 'Casino Review' : 'Knowledge Base'}</p>
                            </div>
                            ${data.url ? `<a href="${data.url}" target="_blank" rel="noopener noreferrer" class="flex items-center space-x-1 px-3 py-1.5 text-xs text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 hover:bg-gray-100 dark:hover:bg-slate-700/50 rounded-lg transition-colors"><span>Read</span><span>↗</span></a>` : ''}
                        </div>
                    </div>
                `;
            } else {
                // Hide raw JSON from users - return empty string
                return '';
            }
        },
        
        
        scrollToBottom() {
            console.log('scrollToBottom');
            if (this.$refs.chatMessages) {
                this.$refs.chatMessages.scrollTop = this.$refs.chatMessages.scrollHeight;
                // Mark that auto-scrolling has happened
                this.hasAutoScrolled = true;
                // Show the scroll to top button when auto-scrolling (if there's an assistant message)
                const hasAssistantMessage = this.messages.some(msg => msg.role === 'assistant');
                if (hasAssistantMessage) {
                    this.showScrollToTop = true;
                    this.highlightScrollButton();
                }
            }
        },
        
        scrollToCurrentMessage() {
            if (this.lastAssistantMessageId) {
                const messageElement = document.getElementById(this.lastAssistantMessageId);
                if (messageElement) {
                    const elementTop = messageElement.offsetTop;
                    const scrollTop = elementTop - 20; // Scroll to 20px above the message
                    
                    this.$refs.chatMessages.scrollTo({
                        top: scrollTop,
                        behavior: 'smooth'
                    });
                }
            }
        },
        
        highlightScrollButton() {
            // Just ensure the button is visible - no visual effects
            // This function is kept for consistency but does nothing
        },
        
        getThreadId() {
            // Use the thread ID from the current chat
            return '{{ $threadId }}';
        },
        
        cancelMessage() {
            // Abort the current request if controller exists
            if (this.controller) {
                this.controller.abort();
                this.controller = null;
            }
            
            // Reset streaming state
            this.isStreaming = false;
            
            // Add a system message to indicate cancellation
            this.messages.push({
                role: 'assistant',
                content: 'Request cancelled by user.',
                timestamp: new Date().toISOString(),
                cancelled: true
            });
            
            // Sync with Livewire and save to database
            @this.set('messages', this.messages);
            @this.call('saveChatHistory');
            
            // Refocus the input field
            this.$nextTick(() => {
                if (this.$refs.messageInput) {
                    this.$refs.messageInput.focus();
                }
            });
        },
        
        async sendMessage() {
            if (!this.currentInput.trim() || this.isStreaming) return;
            
            const userMessage = this.currentInput;
            this.currentInput = '';
            
            // Add user message
            this.messages.push({
                role: 'user',
                content: userMessage,
                timestamp: new Date().toISOString()
            });
            
            // Scroll to bottom after user message
            this.$nextTick(() => {
                this.scrollToBottom();
            });
            
            // Prepare assistant message with loading dice
            let assistantMessage = {
                role: 'assistant',
                content: '',
                formattedContent: '<div class="flex items-center space-x-3"><div class="parsing-indicator"><div class="parsing-dot"></div><div class="parsing-dot"></div><div class="parsing-dot"></div></div><span class="text-sm text-gray-600 dark:text-white/60">🎲 Rolling the dice and parsing results...</span></div>',
                timestamp: new Date().toISOString()
            };
            
            this.messages.push(assistantMessage);
            this.isStreaming = true;
            // Track the latest assistant message ID
            this.lastAssistantMessageId = `message-${this.messages.length - 1}`;
            // Don't show button yet - wait for first auto-scroll
            
            let currentMessageId = null;
            let pendingMessageId = null;
            
            try {
                // Create new AbortController for this request
                this.controller = new AbortController();
                
                const requestBody = {
                    messages: [{
                        role: 'user',
                        content: userMessage
                    }],
                    threadId: this.getThreadId(),
                    resourceId: "bonusesai-agent",
                    options: {
                        temperature: 0.7
                    }
                };
                
                
                const response = await fetch('{{ config("services.ai_agent.url") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    signal: this.controller.signal,
                    body: JSON.stringify(requestBody)
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                
                let buffer = '';
                
                while (true) {
                    const { done, value } = await reader.read();
                    if (done) break;
                    
                    buffer += decoder.decode(value, { stream: true });
                    const lines = buffer.split('\n');
                    buffer = lines.pop() || ''; // Keep incomplete line in buffer
                    
                    for (const line of lines) {
                        if (!line.trim()) continue;
                        
                        // Check if line starts with a prefix (f:, 9:, a:, 0:, e:, d:)
                        if (line.match(/^[f9a0ed]:/)) {
                            const prefix = line.charAt(0);
                            const jsonData = line.substring(2); // Remove the prefix and colon
                            
                            try {
                                if (prefix === '0') {
                                    // Content chunk - extract text between quotes
                                    let content = '';
                                    
                                    if (jsonData.startsWith('"') && jsonData.endsWith('"')) {
                                        // Content is wrapped in quotes
                                        const match = jsonData.match(/^"(.*)"$/);
                                        if (match) {
                                            content = match[1];
                                        }
                                    } else {
                                        // Content is not wrapped in quotes (raw text)
                                        content = jsonData;
                                    }
                                    
                                    if (content) {
                                        // Check if we need to create a new message for pending message ID
                                        if (pendingMessageId) {
                                            // Only create a new message if the current one already has content
                                            const lastMessageIndex = this.messages.length - 1;
                                            const lastMessage = this.messages[lastMessageIndex];
                                            
                                            if (lastMessage && lastMessage.content && lastMessage.content.trim() !== '') {
                                                assistantMessage = {
                                                    role: 'assistant',
                                                    content: '',
                                                    timestamp: new Date().toISOString()
                                                };
                                                
                                                this.messages.push(assistantMessage);
                                                // Update the latest assistant message ID
                                                this.lastAssistantMessageId = `message-${this.messages.length - 1}`;
                                            }
                                            currentMessageId = pendingMessageId;
                                            pendingMessageId = null;
                                            console.log('🆕 Updated message for content, ID:', currentMessageId);
                                        }
                                        
                                        // Unescape the content
                                        content = content
                                            .replace(/\\n/g, '\n')
                                            .replace(/\\"/g, '"')
                                            .replace(/\\\\/g, '\\');
                                        
                                        // Clear the initial loading content on first content chunk
                                        if (assistantMessage.content === '') {
                                            assistantMessage.formattedContent = '';
                                        }
                                        
                                        // Update the existing assistant message content (accumulate)
                                        assistantMessage.content += content;
                                        const formatted = this.formatContent(assistantMessage.content, false); // Use placeholders during streaming
                                        assistantMessage.formattedContent = formatted.content;
                                        assistantMessage.jsonSnippets = formatted.jsonSnippets;
                                        
                                        // Update the last message in the messages array
                                        const lastMessageIndex = this.messages.length - 1;
                                        if (lastMessageIndex >= 0) {
                                            this.messages[lastMessageIndex] = {
                                                ...this.messages[lastMessageIndex],
                                                content: assistantMessage.content,
                                                formattedContent: assistantMessage.formattedContent,
                                                jsonSnippets: assistantMessage.jsonSnippets
                                            };
                                        }
                                        
                                        // Content is being streamed
                                        
                                        // Scroll to bottom after content update
                                        this.$nextTick(() => {
                                            this.scrollToBottom();
                                        });
                                    }
                                } else if (prefix === 'e') {
                                    // Stream ended
                                    console.log('🔄 Stream ended');
                                } else if (prefix === 'f') {
                                    // New message ID - store it but don't create message until we have content
                                    const newMessageId = JSON.parse(jsonData).messageId;
                                    console.log('📨 New Message ID:', newMessageId);
                                    
                                    if (currentMessageId === null) {
                                        // First message ID - just set it
                                        currentMessageId = newMessageId;
                                        console.log('🎯 Set initial message ID:', newMessageId);
                                    } else if (currentMessageId !== newMessageId) {
                                        // Store the new message ID but don't create the message yet
                                        pendingMessageId = newMessageId;
                                        console.log('📝 Pending new message ID:', newMessageId);
                                    }
                                } else if (prefix === 'd') {
                                    // End of stream - final cleanup
                                    console.log('🏁 Stream ended:', jsonData);
                                }
                            } catch (e) {
                                console.error('Error parsing JSON data:', jsonData, e);
                            }
                        } else {
                            // Try to parse as regular JSON (fallback)
                            try {
                                const data = JSON.parse(line);
                                console.log('🔍 Parsed as regular JSON:', data);
                            } catch (e) {
                                console.log('⚠️ Line is not JSON:', line);
                            }
                        }
                    }
                    
                    // Scroll to bottom
                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                
                // Check if error was due to abort
                if (error.name === 'AbortError') {
                    console.log('🛑 Request was cancelled');
                    // Don't add error message for cancelled requests
                    // Remove the last assistant message if it was empty
                    if (assistantMessage.content === '') {
                        this.messages.pop();
                    }
                } else {
                    assistantMessage.content = 'Sorry, there was an error connecting to the AI service.';
                }
            } finally {
                this.isStreaming = false;
                this.controller = null; // Clear the controller reference
                // Ensure the scroll to top button is visible after streaming ends
                setTimeout(() => {
                    this.showScrollToTop = true;
                }, 500);
                
                // Refocus the input field when streaming ends
                this.$nextTick(() => {
                    if (this.$refs.messageInput) {
                        this.$refs.messageInput.focus();
                    }
                });
                
                
                console.log('🏁 Streaming finished');
                console.log('🔍 Final messages state:', this.messages);
                
                // Final JSON rendering - process all messages to render JSON
                this.messages.forEach((message, index) => {
                    // Re-format content with actual JSON HTML
                    const formatted = this.formatContent(message.content, true); // renderJson = true
                    if (formatted.jsonSnippets && formatted.jsonSnippets.length > 0) {
                        message.formattedContent = formatted.content;
                        message.jsonSnippets = formatted.jsonSnippets;
                        
                        // Update the message in the array
                        this.messages[index] = {
                            ...message,
                            formattedContent: formatted.content,
                            jsonSnippets: formatted.jsonSnippets
                        };
                    }
                });
                
                // Sync final state with Livewire and save to database
                @this.set('messages', this.messages);
                @this.call('saveChatHistory');
            }
        }
    }
}

// Make sure the function is available globally
window.chatInterface = chatInterface;

// Pin casino functionality (placeholder)
window.pinCasino = function(buttonElement, casinoName) {
    console.log('Pinning casino:', casinoName);
    
    // Toggle pinned state
    const isPinned = buttonElement.classList.contains('pinned');
    const iconElement = buttonElement.querySelector('.pin-icon');
    
    if (isPinned) {
        // Unpin - remove orange styling from icon only
        buttonElement.classList.remove('pinned');
        iconElement.classList.remove('text-orange-500');
        iconElement.classList.add('text-gray-600/80', 'dark:text-white/80');
        buttonElement.title = 'Pin casino for later';
        console.log('Unpinned:', casinoName);
        alert(`Unpinned ${casinoName}!`);
    } else {
        // Pin - add orange styling to icon only
        buttonElement.classList.add('pinned');
        iconElement.classList.remove('text-gray-600/80', 'dark:text-white/80');
        iconElement.classList.add('text-orange-500');
        buttonElement.title = 'Unpin casino';
        console.log('Pinned:', casinoName);
        alert(`Pinned ${casinoName} for later use!`);
    }
    
    // TODO: Implement actual pinning functionality (save to database, etc.)
};
</script>
