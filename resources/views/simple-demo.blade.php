<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel AI SDK Showcase - Working Demo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
<script type="application/ld+json">
@php
    $structuredData = [
        "@context" => "https://schema.org",
        "@type" => "Person",
        "@id" => "https://senior-mohammad-ali.vercel.app/#me",
        "name" => "Mohammad Ali Abd Al-Wahed",
        "alternateName" => ["aliabdm", "Mohammad Ali Abdul Wahed"],
        "url" => "https://senior-mohammad-ali.vercel.app/",
        "image" => "https://senior-mohammad-ali.vercel.app/og-image.jpg",
        "jobTitle" => "Senior Software Engineer",
        "worksFor" => [
            "@type" => "Organization",
            "name" => "Bayt.com"
        ],
        "sameAs" => [
            "https://github.com/aliabdm",
            "https://www.linkedin.com/in/mohammad-ali-abd-al-wahed",
            "https://medium.com/@aliabdm",
            "https://dev.to/maliano63717738"
        ],
        "knowsAbout" => [
            "Laravel",
            "System Architecture",
            "Backend Engineering",
            "PostgreSQL",
            "Redis",
            "SaaS",
            "AI Integration",
            "Docker"
        ]
    ];
@endphp

{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>

</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-6xl">

        <!-- Header with Mode Switcher -->
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg p-6 mb-8">
            <h1 class="text-3xl font-bold mb-4">🚀 Laravel AI SDK Showcase</h1>
            <p class="text-purple-100 mb-6">Working demo with Mock and Real modes</p>

            <!-- Mode Switcher -->
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-white font-medium">Current Mode:</span>
                    <span class="text-white font-bold text-lg" id="currentMode">{{ config('ai.mode') === 'mock' ? '🧪 Mock' : '🚀 Real' }}</span>
                </div>
                <div class="flex gap-3">
                    <button onclick="switchMode('mock')" class="flex-1 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                        🧪 Mock Mode
                    </button>
                    <button onclick="switchMode('real')" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                        🚀 Real Mode
                    </button>
                </div>
                <div class="mt-2 text-xs text-white/80">
                    Mock: No API keys | Real: Requires API keys in .env
                </div>
            </div>

            <!-- Demo Links -->
            <div class="mt-4 flex gap-3">
                <a href="/streaming/demo" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition">
                    🌊 Streaming Demo
                </a>
                <a href="/ai/mode" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition">
                    ⚙️ Mode Settings
                </a>
            </div>
        </div>

        <!-- Feature Tabs -->
        <div class="bg-white rounded-lg shadow-lg">
            <!-- Tab Navigation -->
            <div class="border-b">
                <div class="flex">
                    <button onclick="showTab('chat')" class="tab-btn px-6 py-3 font-medium border-b-2 border-purple-600 text-purple-700" data-tab="chat">
                        💬 Chat
                    </button>
                    <button onclick="showTab('image')" class="tab-btn px-6 py-3 font-medium text-gray-600 hover:text-gray-800" data-tab="image">
                        🎨 Image
                    </button>
                    <button onclick="showTab('audio')" class="tab-btn px-6 py-3 font-medium text-gray-600 hover:text-gray-800" data-tab="audio">
                        🔊 Audio
                    </button>
                    <button onclick="showTab('search')" class="tab-btn px-6 py-3 font-medium text-gray-600 hover:text-gray-800" data-tab="search">
                        🔍 Search
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Chat Tab -->
                <div id="chat-tab" class="tab-content">
                    <h3 class="text-xl font-bold mb-4">💬 AI Chat</h3>
                    <div class="mb-4">
                        <textarea id="chatInput" class="w-full p-3 border rounded-lg" rows="3" placeholder="Type your message here..."></textarea>
                    </div>
                    <button onclick="sendChat()" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                        Send Message
                    </button>
                    <div id="chatResponse" class="mt-4 p-4 bg-gray-100 rounded-lg min-h-[100px]"></div>
                </div>

                <!-- Image Tab -->
                <div id="image-tab" class="tab-content hidden">
                    <h3 class="text-xl font-bold mb-4">🎨 Image Generation</h3>
                    <div class="mb-4">
                        <input id="imagePrompt" type="text" class="w-full p-3 border rounded-lg" placeholder="Describe an image...">
                    </div>
                    <button onclick="generateImage()" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                        Generate Image
                    </button>
                    <div id="imageResponse" class="mt-4 p-4 bg-gray-100 rounded-lg min-h-[100px]"></div>
                </div>

                <!-- Audio Tab -->
                <div id="audio-tab" class="tab-content hidden">
                    <h3 class="text-xl font-bold mb-4">🔊 Audio Generation</h3>
                    <div class="mb-4">
                        <textarea id="audioText" class="w-full p-3 border rounded-lg" rows="3" placeholder="Text to convert to speech..."></textarea>
                    </div>
                    <button onclick="generateAudio()" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                        Generate Audio
                    </button>
                    <div id="audioResponse" class="mt-4 p-4 bg-gray-100 rounded-lg min-h-[100px]"></div>
                </div>

                <!-- Search Tab -->
                <div id="search-tab" class="tab-content hidden">
                    <h3 class="text-xl font-bold mb-4">🔍 Vector Search</h3>
                    <div class="mb-4">
                        <input id="searchQuery" type="text" class="w-full p-3 border rounded-lg" placeholder="Search for articles...">
                    </div>
                    <button onclick="performSearch()" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700">
                        Search
                    </button>
                    <div id="searchResponse" class="mt-4 p-4 bg-gray-100 rounded-lg min-h-[100px]"></div>
                </div>
            </div>
        </div>

        <!-- Status Bar -->
        <div class="mt-6 bg-white rounded-lg p-4 shadow">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Status: <span id="status" class="font-medium">Ready</span></span>
                <span class="text-sm text-gray-600">Mode: <span id="statusMode" class="font-medium">{{ config('ai.mode') === 'mock' ? 'Mock' : 'Real' }}</span></span>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });

            // Remove active state from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-b-2', 'border-purple-600', 'text-purple-700');
                btn.classList.add('text-gray-600');
            });

            // Show selected tab
            document.getElementById(tabName + '-tab').classList.remove('hidden');

            // Add active state to clicked button
            const activeBtn = document.querySelector(`[data-tab="${tabName}"]`);
            activeBtn.classList.add('border-b-2', 'border-purple-600', 'text-purple-700');
            activeBtn.classList.remove('text-gray-600');
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
                    // Update UI immediately
                    document.getElementById('currentMode').textContent = mode === 'mock' ? '🧪 Mock' : '🚀 Real';
                    document.getElementById('statusMode').textContent = mode === 'mock' ? 'Mock' : 'Real';
                    updateStatus('Switched to ' + mode + ' mode successfully!');
                    clearAllResponses();

                    // Show success message
                    showMessage('Switched to ' + mode + ' mode successfully!', 'success');
                } else {
                    updateStatus('Failed to switch mode');
                    showMessage('Failed to switch mode', 'error');
                }
            })
            .catch(error => {
                console.error('Error switching mode:', error);
                updateStatus('Failed to switch mode');
                showMessage('Network error: ' + error.message, 'error');
            });
        }

        function showMessage(message, type) {
            const colors = {
                success: 'bg-green-100 border-green-400 text-green-700',
                error: 'bg-red-100 border-red-400 text-red-700'
            };

            const alertDiv = document.createElement('div');
            alertDiv.className = `fixed top-4 right-4 p-4 rounded-lg border ${colors[type]} shadow-lg z-50 max-w-md`;
            alertDiv.textContent = message;
            document.body.appendChild(alertDiv);

            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }

        function sendChat() {
            const input = document.getElementById('chatInput').value;
            if (!input.trim()) return;

            updateStatus('Sending message...');
            const responseDiv = document.getElementById('chatResponse');
            responseDiv.textContent = 'Processing...';

            fetch('/chat/demo', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: input })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    responseDiv.innerHTML = `
                        <div class="mb-2"><strong>You:</strong> ${input}</div>
                        <div><strong>AI:</strong> ${data.response}</div>
                        <div class="text-xs text-gray-500 mt-2">Mode: ${data.mode}</div>
                    `;
                    document.getElementById('chatInput').value = '';
                    updateStatus('Message sent successfully!');
                } else {
                    if (data.needs_keys) {
                        responseDiv.innerHTML = `
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="text-yellow-800 font-medium">⚠️ API Keys Required</div>
                                <div class="text-yellow-700 text-sm mt-1">${data.error}</div>
                                <div class="text-yellow-600 text-xs mt-2">Switch to Mock mode or add API keys to .env file</div>
                            </div>
                        `;
                    } else {
                        responseDiv.innerHTML = `
                            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="text-red-800 font-medium">❌ Error</div>
                                <div class="text-red-700 text-sm mt-1">${data.error}</div>
                            </div>
                        `;
                    }
                    updateStatus('Failed to send message');
                }
            })
            .catch(error => {
                responseDiv.innerHTML = `
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="text-red-800 font-medium">❌ Network Error</div>
                        <div class="text-red-700 text-sm mt-1">${error.message}</div>
                    </div>
                `;
                updateStatus('Failed to send message');
            });
        }

        function generateImage() {
            const prompt = document.getElementById('imagePrompt').value;
            if (!prompt.trim()) return;

            updateStatus('Generating image...');
            const responseDiv = document.getElementById('imageResponse');
            responseDiv.textContent = 'Generating...';

            fetch('/image/demo', {
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
                    responseDiv.innerHTML = `
                        <div class="mb-2"><strong>Prompt:</strong> ${prompt}</div>
                        <div><strong>Result:</strong> ${data.url}</div>
                        <div class="text-xs text-gray-500 mt-2">Mode: ${data.mode}</div>
                    `;
                    document.getElementById('imagePrompt').value = '';
                    updateStatus('Image generated successfully!');
                } else {
                    if (data.needs_keys) {
                        responseDiv.innerHTML = `
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="text-yellow-800 font-medium">⚠️ API Keys Required</div>
                                <div class="text-yellow-700 text-sm mt-1">${data.error}</div>
                                <div class="text-yellow-600 text-xs mt-2">Switch to Mock mode or add API keys to .env file</div>
                            </div>
                        `;
                    } else {
                        responseDiv.innerHTML = `
                            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="text-red-800 font-medium">❌ Error</div>
                                <div class="text-red-700 text-sm mt-1">${data.error}</div>
                            </div>
                        `;
                    }
                    updateStatus('Failed to generate image');
                }
            })
            .catch(error => {
                responseDiv.innerHTML = `
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="text-red-800 font-medium">❌ Network Error</div>
                        <div class="text-red-700 text-sm mt-1">${error.message}</div>
                    </div>
                `;
                updateStatus('Failed to generate image');
            });
        }

        function generateAudio() {
            const text = document.getElementById('audioText').value;
            if (!text.trim()) return;

            updateStatus('Generating audio...');
            const responseDiv = document.getElementById('audioResponse');
            responseDiv.textContent = 'Generating...';

            fetch('/audio/demo', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ text: text })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    responseDiv.innerHTML = `
                        <div class="mb-2"><strong>Text:</strong> ${text}</div>
                        <div><strong>Result:</strong> ${data.url}</div>
                        <div class="text-xs text-gray-500 mt-2">Mode: ${data.mode}</div>
                    `;
                    document.getElementById('audioText').value = '';
                    updateStatus('Audio generated successfully!');
                } else {
                    if (data.needs_keys) {
                        responseDiv.innerHTML = `
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="text-yellow-800 font-medium">⚠️ API Keys Required</div>
                                <div class="text-yellow-700 text-sm mt-1">${data.error}</div>
                                <div class="text-yellow-600 text-xs mt-2">Switch to Mock mode or add API keys to .env file</div>
                            </div>
                        `;
                    } else {
                        responseDiv.innerHTML = `
                            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="text-red-800 font-medium">❌ Error</div>
                                <div class="text-red-700 text-sm mt-1">${data.error}</div>
                            </div>
                        `;
                    }
                    updateStatus('Failed to generate audio');
                }
            })
            .catch(error => {
                responseDiv.innerHTML = `
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="text-red-800 font-medium">❌ Network Error</div>
                        <div class="text-red-700 text-sm mt-1">${error.message}</div>
                    </div>
                `;
                updateStatus('Failed to generate audio');
            });
        }

        function performSearch() {
            const query = document.getElementById('searchQuery').value;
            if (!query.trim()) return;

            updateStatus('Searching...');
            const responseDiv = document.getElementById('searchResponse');
            responseDiv.textContent = 'Searching...';

            fetch('/search/demo', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ query: query })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let resultsHtml = `<div class="mb-2"><strong>Query:</strong> ${query}</div><div><strong>Results:</strong></div>`;
                    if (data.results && data.results.length > 0) {
                        resultsHtml += '<ul class="list-disc pl-5">';
                        data.results.forEach(result => {
                            resultsHtml += `<li class="mb-1">${result.title || result.content}</li>`;
                        });
                        resultsHtml += '</ul>';
                    } else {
                        resultsHtml += '<div>No results found</div>';
                    }
                    resultsHtml += `<div class="text-xs text-gray-500 mt-2">Mode: ${data.mode}</div>`;
                    responseDiv.innerHTML = resultsHtml;
                    document.getElementById('searchQuery').value = '';
                    updateStatus('Search completed!');
                } else {
                    if (data.needs_keys) {
                        responseDiv.innerHTML = `
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="text-yellow-800 font-medium">⚠️ API Keys Required</div>
                                <div class="text-yellow-700 text-sm mt-1">${data.error}</div>
                                <div class="text-yellow-600 text-xs mt-2">Switch to Mock mode or add API keys to .env file</div>
                            </div>
                        `;
                    } else {
                        responseDiv.innerHTML = `
                            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="text-red-800 font-medium">❌ Error</div>
                                <div class="text-red-700 text-sm mt-1">${data.error}</div>
                            </div>
                        `;
                    }
                    updateStatus('Search failed');
                }
            })
            .catch(error => {
                responseDiv.innerHTML = `
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="text-red-800 font-medium">❌ Network Error</div>
                        <div class="text-red-700 text-sm mt-1">${error.message}</div>
                    </div>
                `;
                updateStatus('Search failed');
            });
        }

        function updateStatus(message) {
            document.getElementById('status').textContent = message;
        }

        function clearAllResponses() {
            document.getElementById('chatResponse').textContent = '';
            document.getElementById('imageResponse').textContent = '';
            document.getElementById('audioResponse').textContent = '';
            document.getElementById('searchResponse').textContent = '';
        }
    </script>
    <!-- Footer -->
<footer class="mt-12 bg-gray-800 text-gray-200 py-8">
    <div class="container mx-auto px-4 max-w-6xl flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="text-center md:text-left">
            <h4 class="text-lg font-bold">Mohammad Ali Abd Al-Wahed</h4>
            <p class="text-gray-400 text-sm mt-1">Senior Software Engineer</p>
            <p class="text-gray-500 text-xs mt-1">© {{ date('Y') }} All rights reserved</p>
        </div>

        <div class="flex gap-4">
                        <a href="https://senior-mohammad-ali.vercel.app/" target="_blank" class="hover:text-white transition">Portfolio</a>
            <a href="https://github.com/aliabdm" target="_blank" class="hover:text-white transition">GitHub</a>
            <a href="https://www.linkedin.com/in/mohammad-ali-abd-al-wahed" target="_blank" class="hover:text-white transition">LinkedIn</a>
            <a href="https://medium.com/@aliabdm" target="_blank" class="hover:text-white transition">Medium</a>
            <a href="https://dev.to/maliano63717738" target="_blank" class="hover:text-white transition">Dev.to</a>
        </div>
    </div>
</footer>

</body>
</html>
