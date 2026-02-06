<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Streaming Demo - Laravel AI SDK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">

        <!-- Header with Mode Display -->
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg p-6 mb-6">
            <h1 class="text-3xl font-bold mb-2">🌊 AI Streaming Demo</h1>
            <p class="text-purple-100 mb-4">Experience real-time streaming responses from AI</p>

            <!-- Current Mode Display -->
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-white font-medium">Current Mode:</span>
                        <div class="text-white font-bold text-lg mt-1">
                            {{ config('ai.mode') === 'mock' ? '🧪 Mock Mode' : '🚀 Real Mode' }}
                        </div>
                    </div>
                    <div class="text-right">
                        <a href="/ai/mode" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition">
                            ⚙️ Change Mode
                        </a>
                    </div>
                </div>
                <div class="text-xs text-white/80 mt-2">
                    {{ config('ai.mode') === 'mock' ? 'Simulated streaming with delays' : 'Real AI responses with API keys' }}
                </div>
            </div>
        </div>

        <!-- Explanation Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">📖 What is Streaming?</h2>
            <div class="text-gray-700 space-y-2">
                <p>
                    <strong>Streaming</strong> is when AI responses are delivered in real-time, chunk by chunk,
                    just like how ChatGPT types out responses character by character.
                </p>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <h3 class="font-medium text-purple-800 mb-2">🎯 Try This:</h3>
                    <ul class="text-sm text-purple-700 space-y-1">
                        <li>• Type "Tell me a story about AI" and watch it stream word by word</li>
                        <li>• Try "Explain quantum computing" for token-level streaming</li>
                        <li>• Compare with "Regular Response" to see the difference</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Input Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">💬 Enter Your Prompt</h2>
            <div class="mb-4">
                <textarea
                    id="prompt"
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                    placeholder="Try: Tell me a story about AI, Explain quantum computing, What is Laravel?, How does streaming work?"
                ></textarea>
            </div>

            <div class="flex gap-3">
                <button onclick="streamWords()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    📝 Stream Words
                </button>
                <button onclick="streamTokens()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    🔤 Stream Tokens
                </button>
                <button onclick="regularResponse()" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                    ⏱️ Regular Response
                </button>
                <button onclick="clearOutput()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                    🗑️ Clear
                </button>
            </div>
        </div>

        <!-- Output Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">� Output</h2>

            <!-- Streaming Output -->
            <div id="streamingOutput" class="mb-4 p-4 bg-gray-50 rounded-lg min-h-[200px] font-mono text-sm hidden">
                <div id="streamingContent"></div>
            </div>

            <!-- Regular Output -->
            <div id="regularOutput" class="mb-4 p-4 bg-gray-50 rounded-lg min-h-[200px] hidden">
                <div id="regularContent"></div>
            </div>

            <!-- Status -->
            <div id="status" class="text-sm text-gray-600">
                Ready to stream...
            </div>
        </div>

        <!-- Mode Switcher -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">🎛️ Quick Mode Switch</h2>
            <div class="flex gap-3">
                <button onclick="switchMode('mock')" class="flex-1 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                    🧪 Mock Mode
                </button>
                <button onclick="switchMode('real')" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                    🚀 Real Mode
                </button>
            </div>
            <div class="mt-2 text-xs text-gray-500">
                Switch modes without reloading the page
            </div>
        </div>

        <!-- Navigation -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">🧭 Navigation</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <a href="/" class="block bg-purple-600 text-white text-center px-4 py-3 rounded-lg hover:bg-purple-700 transition">
                    🏠 Main Demo
                </a>
                <a href="/ai/mode" class="block bg-indigo-600 text-white text-center px-4 py-3 rounded-lg hover:bg-indigo-700 transition">
                    ⚙️ Mode Settings
                </a>
            </div>
        </div>
    </div>

    <script>
        let isStreaming = false;
        let currentMode = '{{ config('ai.mode') }}';

        function showOutput(type) {
            // Hide all outputs first
            document.getElementById('streamingOutput').classList.add('hidden');
            document.getElementById('regularOutput').classList.add('hidden');

            // Show the selected output
            if (type === 'streaming') {
                document.getElementById('streamingOutput').classList.remove('hidden');
            } else {
                document.getElementById('regularOutput').classList.remove('hidden');
            }
        }

        function updateStatus(message) {
            document.getElementById('status').textContent = message;
        }

        function clearOutput() {
            document.getElementById('streamingContent').textContent = '';
            document.getElementById('regularContent').textContent = '';
            updateStatus('Output cleared');
        }

        function switchMode(mode) {
            updateStatus('Switching mode...');

            fetch('/ai/mode/switch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ mode: mode })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentMode = mode;
                    updateStatus('Switched to ' + mode + ' mode!');
                    clearOutput();
                } else {
                    updateStatus('Failed to switch mode');
                }
            })
            .catch(error => {
                console.error('Error switching mode:', error);
                updateStatus('Failed to switch mode');
            });
        }

        function streamWords() {
            const prompt = document.getElementById('prompt').value;
            if (!prompt.trim()) {
                updateStatus('Please enter a prompt');
                return;
            }

            if (isStreaming) {
                updateStatus('Already streaming...');
                return;
            }

            showOutput('streaming');
            updateStatus('Starting word streaming...');
            isStreaming = true;

            // Use fetch with streaming response
            fetch('/streaming/words', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ prompt: prompt })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }

                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                let buffer = '';

                function processText(text) {
                    buffer += text;
                    const lines = buffer.split('\n');
                    buffer = lines.pop(); // Keep incomplete line in buffer

                    lines.forEach(line => {
                        if (line.startsWith('data: ')) {
                            try {
                                const data = JSON.parse(line.slice(6));

                                if (data.done) {
                                    isStreaming = false;
                                    updateStatus('Word streaming completed!');
                                    return;
                                }

                                if (data.chunk) {
                                    const contentDiv = document.getElementById('streamingContent');
                                    contentDiv.textContent += data.chunk + ' ';
                                    contentDiv.scrollTop = contentDiv.scrollHeight;
                                }
                            } catch (e) {
                                console.error('Error parsing SSE data:', e);
                            }
                        }
                    });
                }

                function readStream() {
                    return reader.read().then(({ done, value }) => {
                        if (done) {
                            isStreaming = false;
                            updateStatus('Word streaming completed!');
                            return;
                        }

                        const text = decoder.decode(value, { stream: true });
                        processText(text);
                        return readStream();
                    });
                }

                return readStream();
            })
            .catch(error => {
                console.error('Error starting stream:', error);
                isStreaming = false;
                updateStatus('Failed to start streaming: ' + error.message);

                // Fallback to regular response
                regularResponse();
            });
        }

        function streamTokens() {
            const prompt = document.getElementById('prompt').value;
            if (!prompt.trim()) {
                updateStatus('Please enter a prompt');
                return;
            }

            if (isStreaming) {
                updateStatus('Already streaming...');
                return;
            }

            showOutput('streaming');
            updateStatus('Starting token streaming...');
            isStreaming = true;

            // Use fetch with streaming response
            fetch('/streaming/tokens', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ prompt: prompt })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }

                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                let buffer = '';

                function processText(text) {
                    buffer += text;
                    const lines = buffer.split('\n');
                    buffer = lines.pop(); // Keep incomplete line in buffer

                    lines.forEach(line => {
                        if (line.startsWith('data: ')) {
                            try {
                                const data = JSON.parse(line.slice(6));

                                if (data.done) {
                                    isStreaming = false;
                                    updateStatus('Token streaming completed!');
                                    return;
                                }

                                if (data.token) {
                                    const contentDiv = document.getElementById('streamingContent');
                                    contentDiv.textContent += data.token;
                                    contentDiv.scrollTop = contentDiv.scrollHeight;
                                }
                            } catch (e) {
                                console.error('Error parsing SSE data:', e);
                            }
                        }
                    });
                }

                function readStream() {
                    return reader.read().then(({ done, value }) => {
                        if (done) {
                            isStreaming = false;
                            updateStatus('Token streaming completed!');
                            return;
                        }

                        const text = decoder.decode(value, { stream: true });
                        processText(text);
                        return readStream();
                    });
                }

                return readStream();
            })
            .catch(error => {
                console.error('Error starting stream:', error);
                isStreaming = false;
                updateStatus('Failed to start streaming: ' + error.message);

                // Fallback to regular response
                regularResponse();
            });
        }

        function regularResponse() {
            const prompt = document.getElementById('prompt').value;
            if (!prompt.trim()) {
                updateStatus('Please enter a prompt');
                return;
            }

            showOutput('regular');
            updateStatus('Getting regular response...');

            fetch('/streaming/regular', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ prompt: prompt })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('regularContent').textContent = data.response;
                    updateStatus('Regular response received!');
                } else {
                    if (data.needs_keys) {
                        document.getElementById('regularContent').innerHTML = `
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="text-yellow-800 font-medium">⚠️ API Keys Required</div>
                                <div class="text-yellow-700 text-sm mt-1">${data.error}</div>
                                <div class="text-yellow-600 text-xs mt-2">Switch to Mock mode or add API keys to .env file</div>
                            </div>
                        `;
                    } else {
                        document.getElementById('regularContent').innerHTML = `
                            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="text-red-800 font-medium">❌ Error</div>
                                <div class="text-red-700 text-sm mt-1">${data.error}</div>
                            </div>
                        `;
                    }
                    updateStatus('Failed to get response');
                }
            })
            .catch(error => {
                document.getElementById('regularContent').innerHTML = `
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="text-red-800 font-medium">❌ Network Error</div>
                        <div class="text-red-700 text-sm mt-1">${error.message}</div>
                    </div>
                `;
                updateStatus('Network error occurred');
            });
        }

        // Auto-focus on prompt input
        document.getElementById('prompt').focus();
    </script>
</body>
</html>
