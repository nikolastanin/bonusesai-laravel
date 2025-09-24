<div class="flex h-full bg-gray-50 dark:bg-gray-900" 
     x-data="chatInterface()" 
     x-init="init()">
    <!-- Left Side - Chat -->
    <div class="w-1/3 border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex flex-col max-h-screen">
        <!-- Chat Header -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">AI Assistant</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Ask me anything about your flow</p>
            </div>
        </div>
        
        <!-- Chat Messages -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-messages" x-ref="chatMessages">
            <!-- Debug info -->
            <div class="text-xs text-gray-500 mb-2">
                Messages: <span x-text="messages.length"></span> | 
                Streaming: <span x-text="isStreaming"></span> |
                Last message content: <span x-text="messages[messages.length-1]?.content || 'none'"></span>
            </div>
            <template x-for="(message, index) in messages" :key="index">
                <div class="flex items-start space-x-3" 
                     :class="message.role === 'user' ? 'flex-row-reverse space-x-reverse' : ''">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                         :class="message.role === 'user' ? 'bg-gray-500' : 'bg-blue-500'">
                        <svg x-show="message.role === 'assistant'" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        <svg x-show="message.role === 'user'" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 max-w-[80%]">
                        <div class="rounded-lg p-3"
                             :class="message.role === 'user' ? 'bg-gray-100 dark:bg-gray-700' : 'bg-blue-50 dark:bg-blue-900/20'">
                            <p class="text-sm text-gray-900 dark:text-white whitespace-pre-wrap" 
                               x-html="message.formattedContent || message.content"></p>
                        </div>
                        <!-- Tool calls display -->
                        <template x-if="message.toolCalls && message.toolCalls.length > 0">
                            <div class="mt-3 space-y-2">
                                <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                    Tools Used
                                </div>
                                <template x-for="tool in message.toolCalls" :key="tool.id">
                                    <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-4 h-4">
                                                <!-- Loading spinner -->
                                                <svg x-show="!tool.completed" class="animate-spin text-blue-500" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <!-- Checkmark -->
                                                <svg x-show="tool.completed" class="text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="tool.name"></span>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            <span x-show="!tool.completed">Running...</span>
                                            <span x-show="tool.completed">Complete</span>
                                            <!-- Debug info -->
                                            <span class="ml-2 text-red-500" x-text="'[Debug: ' + tool.completed + ']'"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
            
            <!-- Streaming indicator -->
            <div x-show="showLoadingIndicator" class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3">
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms;"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms;"></div>
                            <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chat Input -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex space-x-2">
                <input 
                    type="text" 
                    placeholder="Type your message..." 
                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    x-model="currentInput"
                    @keydown.enter="sendMessage()"
                    :disabled="isStreaming"
                    x-ref="messageInput"
                >
                <button 
                    x-show="!isStreaming"
                    class="w-12 h-12 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 border-4 border-[#e58ac6] rounded-full text-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                    @click="sendMessage()"
                    :disabled="!currentInput.trim()"
                    title="Send message"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
                <button 
                    x-show="isStreaming"
                    class="w-12 h-12 bg-white text-black border-4 border-red-300 hover:border-red-400 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center"
                    @click="cancelMessage()"
                    title="Stop current request"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Right Side - Iframe Preview -->
    <div class="flex-1 flex flex-col max-h-screen">
        <!-- Preview Header -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Flow Preview</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Live preview of your funnel</p>
                    <!-- Server Status Indicator -->
                    <div x-show="serverStatus" class="mt-2">
                        <div x-show="serverStatus === 'starting'" class="flex items-center space-x-2 text-blue-600 dark:text-blue-400">
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span class="text-sm font-medium">Starting preview...</span>
                        </div>
                        <div x-show="serverStatus === 'success'" class="flex items-center space-x-2 text-green-600 dark:text-green-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm font-medium">Preview ready</span>
                        </div>
                        <div x-show="serverStatus === 'error'" class="flex items-center space-x-2 text-red-600 dark:text-red-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="text-sm font-medium" x-text="serverErrorMessage"></span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-200 shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Publish
                    </button>
                    <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-600 to-slate-600 text-white text-sm font-medium rounded-lg hover:from-gray-700 hover:to-slate-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-200 shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Save Draft
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Iframe Container -->
        <div class="flex-1 p-4">
            <div class="w-full h-full bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Show placeholder when no preview URL -->
                <div x-show="!previewUrl" class="w-full h-full flex items-center justify-center bg-gray-50 dark:bg-gray-900">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Preview Available</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Ask the AI to create or serve a preview to see your flow here</p>
                    </div>
                </div>
                
                <!-- Show iframe when preview URL is available -->
                <iframe 
                    x-show="previewUrl"
                    :src="previewUrl" 
                    class="w-full h-full border-0"
                    id="flow-preview"
                    title="Flow Preview"
                ></iframe>
            </div>
        </div>
    </div>
</div>

<script>
console.log('Loading chatInterface function...');
function chatInterface() {
    console.log('chatInterface function called');
    return {
        messages: @json($messages),
        currentInput: '',
        isStreaming: false,
        showLoadingIndicator: false,
        previewUrl: @json($previewUrl ?? ''),
        controller: null,
        serverStatus: null, // 'starting', 'success', 'error', or null
        serverErrorMessage: '',
        
        init() {
            // Load messages from Livewire (database)
            const livewireMessages = @json($messages);
            this.messages = livewireMessages || [];
            
            // Load preview URL from Livewire (database)
            const livewirePreviewUrl = @json($previewUrl ?? '');
            this.previewUrl = livewirePreviewUrl || '';
            
            // Sync with Livewire whenever messages change (but don't auto-save during streaming)
            this.$watch('messages', value => {
                // Update Livewire component
                @this.set('messages', value);
                // Only auto-save if not currently streaming to avoid excessive database calls
                if (!this.isStreaming) {
                    @this.call('saveChatHistory');
                }
            });
            
            // Watch for preview URL changes and save them to database
            this.$watch('previewUrl', value => {
                @this.call('savePreviewUrl', value);
            });
            
            // Focus the input field on page load
            this.$nextTick(() => {
                if (this.$refs.messageInput) {
                    this.$refs.messageInput.focus();
                }
            });
            
            // Check if we have a workspace ID and restart the server
            this.checkAndRestartServer();
        },
        
        async checkAndRestartServer() {
            // Check if we have a workspace ID (from the flow)
            const workspaceId = '{{ $flow->getWorkspaceId() }}';
            
            if (workspaceId) {
                console.log('🔄 Found workspace ID, restarting server...');
                this.serverStatus = 'starting';
                
                try {
                    const result = await @this.call('restartServer');
                    
                    if (result.success) {
                        this.serverStatus = 'success';
                        console.log('✅ Server restarted successfully:', result.message);
                    } else {
                        this.serverStatus = 'error';
                        this.serverErrorMessage = result.message;
                        console.error('❌ Server restart failed:', result.message);
                    }
                } catch (error) {
                    this.serverStatus = 'error';
                    this.serverErrorMessage = 'Failed to restart server';
                    console.error('❌ Server restart error:', error);
                }
            } else {
                console.log('ℹ️ No workspace ID found, skipping server restart');
            }
        },

        formatToolName(toolName) {
            // Convert camelCase to readable format
            return toolName
                .replace(/([A-Z])/g, ' $1')
                .replace(/^./, str => str.toUpperCase())
                .trim();
        },
        
        formatContent(content) {
            // Convert markdown bold to HTML
            return content
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/`(.*?)`/g, '<code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-800 rounded text-sm">$1</code>');
        },
        


        
        scrollToBottom() {
            if (this.$refs.chatMessages) {
                this.$refs.chatMessages.scrollTop = this.$refs.chatMessages.scrollHeight;
            }
        },
        
        getThreadId() {
            // Generate a stable thread ID based on the flow ID
            const flowId = '{{ $flow->id }}';
            return `flow-${flowId}-thread`;
        },
        
        cancelMessage() {
            console.log('🛑 Cancelling current request...');
            
            // Abort the current request if controller exists
            if (this.controller) {
                this.controller.abort();
                this.controller = null;
                console.log('✅ Request aborted');
            }
            
            // Reset streaming state
            this.isStreaming = false;
            this.showLoadingIndicator = false;
            
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
            
            console.log('🏁 Request cancellation complete');
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
            
            // Prepare assistant message
            let assistantMessage = {
                role: 'assistant',
                content: '',
                toolCalls: [],
                timestamp: new Date().toISOString()
            };
            
            this.messages.push(assistantMessage);
            this.isStreaming = true;
            this.showLoadingIndicator = true;
            
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
                    resourceId: "flowbudy-funnel-builder",
                    options: {
                        temperature: 0.7
                    }
                };
                
                console.log('🔍 Sending request:', JSON.stringify(requestBody, null, 2));
                console.log('🧵 Thread ID:', this.getThreadId());
                
                const response = await fetch('http://localhost:4111/api/agents/contextAwareAgent/stream', {
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
                
                console.log('Stream started, reading response...');
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
                                            assistantMessage = {
                                                role: 'assistant',
                                                content: '',
                                                toolCalls: [],
                                                timestamp: new Date().toISOString()
                                            };
                                            
                                            this.messages.push(assistantMessage);
                                            currentMessageId = pendingMessageId;
                                            pendingMessageId = null;
                                            console.log('🆕 Created new message for content, ID:', currentMessageId);
                                        }
                                        
                                        // Unescape the content
                                        content = content
                                            .replace(/\\n/g, '\n')
                                            .replace(/\\"/g, '"')
                                            .replace(/\\\\/g, '\\');
                                        
                                        // Update the existing assistant message content (accumulate)
                                        assistantMessage.content += content;
                                        assistantMessage.formattedContent = this.formatContent(assistantMessage.content);
                                        
                                        // Update the last message in the messages array
                                        const lastMessageIndex = this.messages.length - 1;
                                        if (lastMessageIndex >= 0) {
                                            this.messages[lastMessageIndex] = {
                                                ...this.messages[lastMessageIndex],
                                                content: assistantMessage.content,
                                                formattedContent: assistantMessage.formattedContent
                                            };
                                        }
                                        
                                        // Show loading indicator while content is being streamed
                                        this.showLoadingIndicator = true;
                                        
                                        // Scroll to bottom after content update
                                        this.$nextTick(() => {
                                            this.scrollToBottom();
                                        });
                                    }
                                } else if (prefix === '9') {
                                    // Tool call start
                                    const toolData = JSON.parse(jsonData);
                                    
                                    // Check if we need to create a new message for pending message ID
                                    if (pendingMessageId) {
                                        assistantMessage = {
                                            role: 'assistant',
                                            content: '',
                                            toolCalls: [],
                                            timestamp: new Date().toISOString()
                                        };
                                        
                                        this.messages.push(assistantMessage);
                                        currentMessageId = pendingMessageId;
                                        pendingMessageId = null;
                                        console.log('🆕 Created new message for tool, ID:', currentMessageId);
                                    }
                                    
                                    const toolCall = {
                                        id: toolData.toolCallId,
                                        name: this.formatToolName(toolData.toolName),
                                        args: toolData.args,
                                        completed: false
                                    };
                                    
                                    // Ensure toolCalls array exists on the assistant message
                                    if (!assistantMessage.toolCalls) {
                                        assistantMessage.toolCalls = [];
                                    }
                                    
                                    // Add tool call to the existing assistant message
                                    assistantMessage.toolCalls.push(toolCall);
                                    
                                    // Update the last message in the messages array to include the tool call
                                    const lastMessageIndex = this.messages.length - 1;
                                    if (lastMessageIndex >= 0) {
                                        this.messages[lastMessageIndex] = {
                                            ...this.messages[lastMessageIndex],
                                            toolCalls: [...assistantMessage.toolCalls]
                                        };
                                    }
                                    
                                    console.log('🛠️ Tool:', toolCall.name);
                                    console.log('🔍 Tool calls after adding:', assistantMessage.toolCalls.length);
                                    console.log('🔍 All current tools:', assistantMessage.toolCalls.map(t => t.name));
                                    
                                    // Force UI update to show tool call immediately
                                    this.$nextTick(() => {
                                        this.scrollToBottom();
                                    });
                                } else if (prefix === 'a') {
                                    // Tool call result
                                    const resultData = JSON.parse(jsonData);
                                    
                                    // Find the tool call in the assistant message
                                    const toolCallIndex = assistantMessage.toolCalls.findIndex(t => t.id === resultData.toolCallId);
                                    
                                    if (toolCallIndex !== -1) {
                                        // Create a completely new tool call object to ensure reactivity
                                        const updatedToolCall = { 
                                            ...assistantMessage.toolCalls[toolCallIndex], 
                                            completed: true 
                                        };
                                        
                                        // Update the tool call in the assistant message
                                        assistantMessage.toolCalls[toolCallIndex] = updatedToolCall;
                                        
                                        // Update the last message in the messages array
                                        const lastMessageIndex = this.messages.length - 1;
                                        if (lastMessageIndex >= 0) {
                                            this.messages[lastMessageIndex] = {
                                                ...this.messages[lastMessageIndex],
                                                toolCalls: [...assistantMessage.toolCalls]
                                            };
                                        }
                                        
                                        console.log('✅ Tool completed:', updatedToolCall.name);
                                        console.log('🔍 Tool call state after update:', updatedToolCall);
                                        
                                        // Special logging for Serve Preview tool
                                        if (updatedToolCall.name === 'Serve Preview') {
                                            console.log('🌐 Serve Preview Results:', resultData);
                                            
                                            // Update iframe src if URL is available
                                            if (resultData.result && resultData.result.url) {
                                                const newPreviewUrl = resultData.result.url;
                                                
                                                console.log('🔄 Updating iframe src to:', newPreviewUrl);
                                                console.log('🔍 Current previewUrl:', this.previewUrl);
                                                
                                                // Use the direct URL from the AI response
                                                let finalPreviewUrl = newPreviewUrl;
                                                console.log('🔗 Using direct URL:', finalPreviewUrl);
                                                
                                                // Update using Alpine.js reactivity
                                                this.previewUrl = finalPreviewUrl;
                                                
                                                console.log('✅ Preview URL updated successfully');
                                                console.log('🔍 New previewUrl:', this.previewUrl);
                                                
                                                // Also update DOM directly as backup
                                                this.$nextTick(() => {
                                                    const iframe = document.getElementById('flow-preview');
                                                    if (iframe) {
                                                        console.log('🔍 Iframe src after Alpine update:', iframe.src);
                                                        if (iframe.src !== finalPreviewUrl) {
                                                            iframe.src = finalPreviewUrl;
                                                            console.log('🔄 Fallback: Updated iframe src directly');
                                                        }
                                                    }
                                                });
                                            }
                                        }
                                        
                                        // Special handling for Create Project tool
                                        if (updatedToolCall.name === 'Create Project') {
                                            console.log('🏗️ Create Project Results:', resultData);
                                            
                                            // Save project ID as workspace ID if available in the result
                                            if (resultData.result && resultData.result.projectId) {
                                                const projectId = resultData.result.projectId;
                                                console.log('💾 Saving project ID as workspace ID:', projectId);
                                                
                                                // Call Livewire method to save workspace ID (using projectId)
                                                @this.call('saveWorkspaceId', projectId);
                                                
                                                console.log('✅ Project ID saved as workspace ID successfully');
                                            } else {
                                                console.log('⚠️ No project ID found in Create Project result');
                                            }
                                        }
                                        
                                        // Show loading indicator after tool completion to indicate AI is still thinking
                                        this.showLoadingIndicator = true;
                                        
                                        // Scroll to bottom after tool completion
                                        this.$nextTick(() => {
                                            this.scrollToBottom();
                                        });
                                        
                                        console.log('🔄 Messages updated, tool completed:', updatedToolCall.name);
                                        console.log('🔍 Current tool calls count:', assistantMessage.toolCalls.length);
                                        console.log('🔍 All tool calls:', assistantMessage.toolCalls);
                                    }
                                } else if (prefix === 'e') {
                                    // Stream ended - mark all incomplete tools as completed
                                    if (assistantMessage.toolCalls && assistantMessage.toolCalls.length > 0) {
                                        let hasChanges = false;
                                        const updatedToolCalls = assistantMessage.toolCalls.map(tool => {
                                            if (!tool.completed) {
                                                hasChanges = true;
                                                console.log('🔄 Stream ended - marking tool as completed:', tool.name);
                                                return { ...tool, completed: true };
                                            }
                                            return tool;
                                        });
                                        
                                        if (hasChanges) {
                                            // Update the assistant message
                                            assistantMessage.toolCalls = updatedToolCalls;
                                            
                                            // Update the last message in the messages array
                                            const lastMessageIndex = this.messages.length - 1;
                                            if (lastMessageIndex >= 0) {
                                                this.messages[lastMessageIndex] = {
                                                    ...this.messages[lastMessageIndex],
                                                    toolCalls: [...updatedToolCalls]
                                                };
                                            }
                                            
                                            // Scroll to bottom after UI update
                                            this.$nextTick(() => {
                                                this.scrollToBottom();
                                            });
                                            console.log('🔄 Stream end - UI updated for incomplete tools');
                                        }
                                    }
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
                                    
                                    // Ensure all tool calls are marked as completed
                                    if (assistantMessage.toolCalls && assistantMessage.toolCalls.length > 0) {
                                        let hasChanges = false;
                                        const updatedToolCalls = assistantMessage.toolCalls.map(tool => {
                                            if (!tool.completed) {
                                                hasChanges = true;
                                                console.log('🔄 Final stream end - marking tool as completed:', tool.name);
                                                return { ...tool, completed: true };
                                            }
                                            return tool;
                                        });
                                        
                                        // Force UI update if we made changes
                                        if (hasChanges) {
                                            assistantMessage.toolCalls = updatedToolCalls;
                                            
                                            // Create a completely new message object to ensure proper reactivity
                                            const updatedMessage = {
                                                ...assistantMessage,
                                                toolCalls: updatedToolCalls
                                            };
                                            
                                            // Update the messages array with the new message
                                            this.messages = [
                                                ...this.messages.slice(0, -1),
                                                updatedMessage
                                            ];
                                            
                                            console.log('🔄 Final stream end - UI updated for incomplete tools');
                                        }
                                    }
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
                    if (assistantMessage.content === '' && (!assistantMessage.toolCalls || assistantMessage.toolCalls.length === 0)) {
                        this.messages.pop();
                    }
                } else {
                    assistantMessage.content = 'Sorry, there was an error connecting to the AI service.';
                }
            } finally {
                this.isStreaming = false;
                this.showLoadingIndicator = false;
                this.controller = null; // Clear the controller reference
                
                // Refocus the input field when streaming ends
                this.$nextTick(() => {
                    if (this.$refs.messageInput) {
                        this.$refs.messageInput.focus();
                    }
                });
                
                // Ensure all tool calls are marked as completed
                if (assistantMessage.toolCalls && assistantMessage.toolCalls.length > 0) {
                    let hasChanges = false;
                    const updatedToolCalls = assistantMessage.toolCalls.map(tool => {
                        if (!tool.completed) {
                            hasChanges = true;
                            console.log('🔄 Final cleanup - marking tool as completed:', tool.name);
                            return { ...tool, completed: true };
                        }
                        return tool;
                    });
                    
                    if (hasChanges) {
                        // Update the assistant message
                        assistantMessage.toolCalls = updatedToolCalls;
                        
                        // Update the last message in the messages array
                        const lastMessageIndex = this.messages.length - 1;
                        if (lastMessageIndex >= 0) {
                            this.messages[lastMessageIndex] = {
                                ...this.messages[lastMessageIndex],
                                toolCalls: [...updatedToolCalls]
                            };
                        }
                        
                        // Scroll to bottom after final cleanup
                        this.$nextTick(() => {
                            this.scrollToBottom();
                        });
                        console.log('🔄 Final cleanup - UI updated');
                    }
                }
                
                console.log('🏁 Streaming finished');
                console.log('🔍 Final messages state:', this.messages);
                console.log('🔍 Final tool calls state:', assistantMessage.toolCalls);
                console.log('🔍 Last message tool calls:', this.messages[this.messages.length - 1]?.toolCalls);
                
                // Sync final state with Livewire and save to database
                @this.set('messages', this.messages);
                @this.call('saveChatHistory');
            }
        }
    }
}

// Make sure the function is available globally
console.log('chatInterface function defined:', typeof chatInterface);
window.chatInterface = chatInterface;
</script> 