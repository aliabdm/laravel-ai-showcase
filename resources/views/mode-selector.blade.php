<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Mode Selector - Laravel AI SDK Showcase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg p-6 mb-8">
            <h1 class="text-3xl font-bold mb-2">🎛️ AI Mode Selector</h1>
            <p class="text-purple-100">Switch between Mock and Real AI modes</p>
            
            <!-- Current Mode Display -->
            <div class="mt-4 bg-white/10 backdrop-blur-sm rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <span class="text-white font-medium">Current Mode:</span>
                    <span class="text-white font-bold text-lg">
                        {{ $effectiveMode === 'mock' ? '🧪 Mock Mode' : '🚀 Real Mode' }}
                    </span>
                </div>
                <div class="text-xs text-white/80 mt-2">
                    {{ $sessionMode ? 'Session override active' : 'Using .env default' }}
                </div>
            </div>
        </div>

        <!-- Mode Selection -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Select AI Mode</h2>
            
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <!-- Mock Mode Card -->
                <div class="border rounded-lg p-4 {{ $effectiveMode === 'mock' ? 'border-purple-500 bg-purple-50' : 'border-gray-200' }}">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                            🧪
                        </div>
                        <div>
                            <h3 class="font-semibold">Mock Mode</h3>
                            <p class="text-sm text-gray-600">No API keys required</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center">
                            <span class="text-green-500 mr-2">✓</span>
                            <span>Works instantly</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-green-500 mr-2">✓</span>
                            <span>Deterministic responses</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-green-500 mr-2">✓</span>
                            <span>Perfect for development</span>
                        </div>
                    </div>
                    
                    <form method="POST" action="/ai/mode/switch" class="mt-4">
                        @csrf
                        <input type="hidden" name="mode" value="mock">
                        <button type="submit" class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                            Use Mock Mode
                        </button>
                    </form>
                </div>

                <!-- Real Mode Card -->
                <div class="border rounded-lg p-4 {{ $effectiveMode === 'real' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                            🚀
                        </div>
                        <div>
                            <h3 class="font-semibold">Real Mode</h3>
                            <p class="text-sm text-gray-600">Requires API keys</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center">
                            <span class="{{ $hasAnyKeys ? 'text-green-500' : 'text-red-500' }} mr-2">
                                {{ $hasAnyKeys ? '✓' : '✗' }}
                            </span>
                            <span>Actual AI responses</span>
                        </div>
                        <div class="flex items-center">
                            <span class="{{ $hasAnyKeys ? 'text-green-500' : 'text-red-500' }} mr-2">
                                {{ $hasAnyKeys ? '✓' : '✗' }}
                            </span>
                            <span>Production ready</span>
                        </div>
                        <div class="flex items-center">
                            <span class="{{ $hasAnyKeys ? 'text-green-500' : 'text-red-500' }} mr-2">
                                {{ $hasAnyKeys ? '✓' : '✗' }}
                            </span>
                            <span>Multiple providers</span>
                        </div>
                    </div>
                    
                    @if (!$hasAnyKeys)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                            <div class="text-sm">
                                <div class="font-medium text-yellow-800 mb-1">⚠️ API Keys Required</div>
                                <div class="text-yellow-700">Add API keys to your .env file:</div>
                                <div class="text-yellow-600 text-xs mt-2 font-mono">
                                    GEMINI_API_KEY=your_key<br>
                                    OPENAI_API_KEY=your_key<br>
                                    ELEVENLABS_API_KEY=your_key
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <form method="POST" action="/ai/mode/switch" class="mt-4">
                        @csrf
                        <input type="hidden" name="mode" value="real">
                        <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition {{ !$hasAnyKeys ? 'opacity-50 cursor-not-allowed' : '' }}">
                            {{ $hasAnyKeys ? 'Use Real Mode' : 'Add API Keys First' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Clear Override -->
            @if ($sessionMode)
                <div class="border border-orange-200 rounded-lg p-4 bg-orange-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-orange-800">Session Override Active</h3>
                            <p class="text-sm text-orange-700">Clear to use .env default mode</p>
                        </div>
                        <form method="POST" action="/ai/mode/clear" class="inline">
                            @csrf
                            <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition">
                                Clear Override
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <!-- API Keys Status -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">API Keys Status</h2>
            
            <div class="grid md:grid-cols-3 gap-4">
                <div class="border rounded-lg p-4 {{ $hasGeminiKey ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-medium">Gemini</span>
                        <span class="text-sm {{ $hasGeminiKey ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $hasGeminiKey ? '✓ Configured' : '✗ Missing' }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ $hasGeminiKey ? 'Ready for text & images' : 'Add GEMINI_API_KEY to .env' }}
                    </div>
                </div>
                
                <div class="border rounded-lg p-4 {{ $hasOpenAIKey ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-medium">OpenAI</span>
                        <span class="text-sm {{ $hasOpenAIKey ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $hasOpenAIKey ? '✓ Configured' : '✗ Missing' }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ $hasOpenAIKey ? 'Ready for text & transcription' : 'Add OPENAI_API_KEY to .env' }}
                    </div>
                </div>
                
                <div class="border rounded-lg p-4 {{ $hasElevenLabsKey ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-medium">ElevenLabs</span>
                        <span class="text-sm {{ $hasElevenLabsKey ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $hasElevenLabsKey ? '✓ Configured' : '✗ Missing' }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ $hasElevenLabsKey ? 'Ready for audio synthesis' : 'Add ELEVENLABS_API_KEY to .env' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Navigation</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <a href="/" class="block bg-purple-600 text-white text-center px-4 py-3 rounded-lg hover:bg-purple-700 transition">
                    🏠 Main Demo
                </a>
                <a href="/streaming/demo" class="block bg-indigo-600 text-white text-center px-4 py-3 rounded-lg hover:bg-indigo-700 transition">
                    🌊 Streaming Demo
                </a>
            </div>
        </div>
    </div>

    <script>
        // Handle form submissions with AJAX for better UX
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const submitButton = form.querySelector('button[type="submit"]');
                const originalText = submitButton.textContent;
                
                submitButton.textContent = 'Switching...';
                submitButton.disabled = true;
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showMessage(data.message, 'success');
                        // Reload page after a short delay to show the change
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showMessage(data.message || 'Failed to switch mode', 'error');
                    }
                })
                .catch(error => {
                    showMessage('Network error: ' + error.message, 'error');
                })
                .finally(() => {
                    submitButton.textContent = originalText;
                    submitButton.disabled = false;
                });
            });
        });
        
        function showMessage(message, type) {
            const colors = {
                success: 'bg-green-100 border-green-400 text-green-700',
                error: 'bg-red-100 border-red-400 text-red-700'
            };
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `fixed top-4 right-4 p-4 rounded-lg border ${colors[type]} shadow-lg z-50`;
            alertDiv.textContent = message;
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }
    </script>
</body>
</html>
