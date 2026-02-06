# 🚀 Laravel AI SDK Showcase

A **production-ready demonstration** of Laravel's official AI SDK featuring both **Mock Mode** (instant demo) and **Real Mode** (production with API keys). This showcase presents professional Laravel development with AI integration, real-time streaming, and seamless mode switching.

## ✨ Key Features

- 🧪 **Mock Mode**: Works instantly without API keys - perfect for demos and development
- 🚀 **Real Mode**: Production-ready with actual AI providers and API key validation  
- 💬 **Interactive Chat**: AI conversations with context awareness and history
- 🎨 **Image Generation**: Create and edit images with AI-powered text-to-image
- 🔊 **Audio & TTS**: Advanced text-to-speech synthesis with voice options
- 🔍 **Vector Search**: Semantic search with embeddings and similarity scoring
- 📊 **Real-time Streaming**: Character-by-character AI responses with Server-Sent Events
- 🎛️ **Session-based Mode Switching**: Toggle between modes instantly without server restart
- 🛡️ **Comprehensive Error Handling**: Graceful fallbacks and clear user feedback
- 📱 **Responsive UI**: Modern, mobile-friendly interface with Tailwind CSS

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- PostgreSQL (optional, for vector search)
- Git

### 1. Clone and Setup
```bash
git clone <repository-url>
cd laravel-ai-showcase
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Environment Configuration
```bash
# Database (optional - works without)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laravel_ai_showcase
DB_USERNAME=postgres
DB_PASSWORD=password

# AI Mode Configuration
AI_MODE=mock  # Start with mock mode, change to 'real' for production
```

### 3. Database Setup (Optional)
```bash
# Create PostgreSQL database with vector extension
createdb laravel_ai_showcase
psql laravel_ai_showcase -c "CREATE EXTENSION IF NOT EXISTS vector;"

# Run migrations
php artisan migrate
```

### 4. Start Application
```bash
php artisan serve
```

Visit: `http://localhost:8000`

## 🎯 AI Modes Explained

### 🧪 Mock Mode (Default)
**Perfect for demonstrations, development, and testing**

- ✅ **No API keys required** - works immediately after cloning
- ✅ **Deterministic responses** - consistent for testing and demos  
- ✅ **Full feature access** - all tabs work without limitations
- ✅ **Simulated streaming** - realistic delays for demonstration
- ✅ **Development friendly** - instant feedback, no API costs

**When to use Mock Mode:**
- Development and testing environments
- Client demonstrations and presentations
- Learning Laravel AI SDK integration
- When API keys are not available

### 🚀 Real Mode (Production)
**Ready for production with actual AI providers**

- 🔑 **API keys required** - configure in `.env` file
- 🤖 **Actual AI responses** - from Gemini, OpenAI, and other providers
- ⚡ **Real-time streaming** - genuine AI response streaming
- 🏭 **Production features** - all AI capabilities available
- 📊 **Performance metrics** - real response times and quality

**When to use Real Mode:**
- Production applications
- When API keys are available
- For genuine AI interactions
- Performance testing with real providers

## 🔑 API Keys Configuration

### Required for Real Mode
Add these to your `.env` file:

```env
# AI Mode Configuration
AI_MODE=real

# Primary AI Providers (choose one or more)
GEMINI_API_KEY=your_gemini_api_key_here
OPENAI_API_KEY=your_openai_api_key_here
ELEVENLABS_API_KEY=your_elevenlabs_api_key_here

# Optional Additional Providers
ANTHROPIC_API_KEY=your_anthropic_api_key_here
COHERE_API_KEY=your_cohere_api_key_here
DEEPSEEK_API_KEY=your_deepseek_api_key_here
```

### Where to Get API Keys

| Provider | Link | Cost | Free Tier |
|----------|------|-------|-----------|
| **Gemini** | [ai.google.dev](https://ai.google.dev) | ✅ **Completely Free** |
| **OpenAI** | [platform.openai.com](https://platform.openai.com) | ✅ $5 Free Credit |
| **ElevenLabs** | [elevenlabs.io](https://elevenlabs.io) | ✅ Free Tier Available |
| **Anthropic** | [console.anthropic.com](https://console.anthropic.com) | ✅ Free Tier |
| **Cohere** | [cohere.com](https://cohere.com) | ✅ Free Tier |

## 📖 Interactive Demo Guide

### Main Demo (`/`)
The primary interface showcasing all AI capabilities with tabbed navigation.

#### Mode Switcher
- **Visual Indicators**: Current mode displayed with emoji indicators
- **Instant Switching**: Click Mock/Real buttons to toggle modes
- **Session Persistence**: Mode choice remembered during session
- **No Restart Required**: Changes take effect immediately

#### Feature Tabs

**💬 Chat Tab**
```javascript
// Mock Mode Example
fetch('/chat/demo', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ message: 'Hello, how are you?' })
})
.then(response => response.json())
.then(data => {
    console.log(data.response); // AI response
});
```

**🎨 Image Tab**
- Generate images from text prompts
- Multiple style options (Landscape, Portrait, Square)
- Mock mode returns placeholder images
- Real mode uses actual AI image generation

**🔊 Audio Tab**
- Convert text to natural speech
- Voice selection (Male/Female)
- Mock mode returns audio URLs
- Real mode uses actual TTS services

**🔍 Search Tab**
- Vector semantic search with embeddings
- Find similar content using AI
- Mock mode returns sample results
- Real mode uses actual vector search

### Mode Selector (`/ai/mode`)
Dedicated page for advanced mode management.

#### Features
- **Visual Mode Selection**: Choose between Mock and Real modes
- **API Key Status**: See which keys are configured and working
- **Session Override Management**: Switch modes without server restart
- **Clear Override**: Return to `.env` default configuration
- **Real-time Validation**: Immediate feedback on mode changes

#### Usage Examples
```javascript
// Switch to Mock Mode
fetch('/ai/mode/switch', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ mode: 'mock' })
});

// Switch to Real Mode
fetch('/ai/mode/switch', {
    method: 'POST', 
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ mode: 'real' })
});

// Clear Session Override
fetch('/ai/mode/clear', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' }
});
```

### Streaming Demo (`/streaming/demo`)
Educational interface demonstrating real-time AI response streaming.

#### What is Streaming?
Streaming shows AI responses appearing **character by character** as they're generated, rather than waiting for the complete response. This provides:
- ⚡ **Immediate feedback** - Users see responses start immediately
- 📊 **Perceived performance** - Faster apparent response times
- 🎯 **Better UX** - More engaging and interactive experience
- 📱 **Mobile friendly** - Works well on all connection types

#### Streaming Options

**📝 Word Streaming**
- Chunks responses by words
- Natural reading pace
- Good for longer content
- Simulated delays in Mock mode

**🔤 Token Streaming**  
- Character-by-character display
- Most responsive feel
- Ideal for real-time interaction
- Smooth typing effect

**📄 Regular Response**
- Non-streaming comparison
- Shows complete response at once
- Performance benchmarking
- Helps understand streaming benefits

#### Streaming Implementation
```javascript
// Word Streaming Example
fetch('/streaming/words', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ prompt: 'Tell me a story about AI' })
})
.then(response => response.body.getReader())
.then(reader => {
    const decoder = new TextDecoder();
    let buffer = '';
    
    function readStream() {
        return reader.read().then(({ done, value }) => {
            if (done) return;
            
            buffer += decoder.decode(value, { stream: true });
            const lines = buffer.split('\n');
            buffer = lines.pop();
            
            lines.forEach(line => {
                if (line.startsWith('data: ')) {
                    const data = JSON.parse(line.slice(6));
                    if (data.chunk) {
                        // Append word to output
                        outputElement.textContent += data.chunk + ' ';
                    }
                }
            });
            
            readStream();
        });
    }
    
    readStream();
});
```

## 🔧 Technical Implementation

### Architecture Overview
```
┌─────────────────┐
│   Frontend    │  ← Blade Views + JavaScript
├─────────────────┤
│   Controllers  │  ← SimpleDemoController, StreamingController, ModeController
├─────────────────┤
│   Middleware   │  ← AiModeMiddleware (session-based mode override)
├─────────────────┤
│   Providers    │  ← MockProvider (deterministic responses)
├─────────────────┤
│   Laravel AI   │  ← SDK Facades (real AI integration)
└─────────────────┘
```

### Key Components

**Controllers**
- `SimpleDemoController`: Handles chat, image, audio, and search features
- `StreamingController`: Manages real-time streaming responses  
- `ModeController`: Provides session-based mode switching

**Middleware**
- `AiModeMiddleware`: Overrides AI configuration based on session
- Applies mode changes globally across all requests

**Providers**
- `MockProvider`: Complete AI implementation with streaming support
- Deterministic responses for consistent testing
- Generator-based streaming for realistic delays

### Error Handling Strategy
```php
// Graceful Fallback Pattern
try {
    if ($this->shouldUseMock()) {
        $response = $this->getMockProvider()->text($prompt);
    } else {
        $response = \Laravel\Ai\Facades\Ai::text($prompt);
    }
} catch (\Exception $e) {
    return response()->json([
        'success' => false,
        'error' => $e->getMessage(),
        'mode' => $this->shouldUseMock() ? 'mock' : 'real'
    ], 500);
}
```

## 🧪 Testing

### Comprehensive Test Suite
The project includes extensive test coverage ensuring reliability:

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter AiShowcaseTest

# Run with coverage
php artisan test --coverage
```

### Test Coverage Areas
- ✅ **Mock Mode Functionality**: All features work without API keys
- ✅ **Real Mode Validation**: Proper API key requirements and error handling
- ✅ **Mode Switching**: Session-based switching works correctly
- ✅ **Streaming Demo**: Word and token streaming functionality
- ✅ **API Endpoints**: All endpoints respond correctly
- ✅ **Error Handling**: Graceful fallbacks and user feedback
- ✅ **Input Validation**: Proper request validation and sanitization

### Test Results
```
✅ 16 tests passing (55 assertions)
✅ Duration: ~2 seconds
✅ All functionality verified
✅ Production ready
```

## 🚀 Deployment

### Production Deployment

#### Environment Setup
```bash
# Production .env configuration
APP_ENV=production
APP_DEBUG=false
AI_MODE=real

# Required API keys
GEMINI_API_KEY=your_production_key
OPENAI_API_KEY=your_production_key
```

#### Deployment Options

**Railway.app**
```bash
# Deploy to Railway
railway login
railway link
railway up
```

**DigitalOcean**
```bash
# Deploy with Docker
docker build -t laravel-ai-showcase .
docker run -p 8000:8000 laravel-ai-showcase
```

**Traditional Hosting**
```bash
# Standard deployment
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Performance Considerations
- **Caching**: Enable Redis/Memcached for production
- **Database**: Use PostgreSQL with vector extension for search
- **File Storage**: Configure S3 or similar for generated files
- **SSL**: Ensure HTTPS for API key security

## 🛠️ Development

### Adding New AI Features
```php
// 1. Add method to MockProvider
public function newFeature($input) {
    return "Mock response for: " . $input;
}

// 2. Add streaming support
public function streamNewFeature($input) {
    $response = $this->newFeature($input);
    foreach (str_split($response, 3) as $chunk) {
        yield $chunk;
        usleep(100000); // 100ms delay
    }
}

// 3. Add controller method
public function newFeature(Request $request) {
    if ($this->shouldUseMock()) {
        $response = $this->getMockProvider()->newFeature($request->input('prompt'));
    } else {
        $response = \Laravel\Ai\Facades\Ai::newFeature($request->input('prompt'));
    }
    
    return response()->json([
        'success' => true,
        'response' => $response,
        'mode' => $this->shouldUseMock() ? 'mock' : 'real'
    ]);
}
```

### Code Quality Standards
- **PSR-12**: Follow PHP standards
- **Laravel Conventions**: Use framework best practices
- **Type Safety**: Include type hints and return types
- **Error Handling**: Comprehensive try-catch blocks
- **Testing**: Write tests for all new features

## 📊 Project Structure

```
laravel-ai-showcase/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── SimpleDemoController.php
│   │   │   ├── StreamingController.php
│   │   │   └── ModeController.php
│   │   └── Middleware/
│   │       └── AiModeMiddleware.php
│   └── Ai/
│       └── Providers/
│           └── MockProvider.php
├── resources/
│   └── views/
│       ├── simple-demo.blade.php
│       ├── streaming-demo.blade.php
│       └── mode-selector.blade.php
├── tests/
│   └── Feature/
│       └── AiShowcaseTest.php
├── database/
│   └── migrations/
├── routes/
│   └── web.php
├── .env.example
├── composer.json
└── README.md
```

## 🔍 Troubleshooting

### Common Issues

**API Key Errors**
```bash
# Check if keys are properly set
php artisan tinker
>>> env('GEMINI_API_KEY');
>>> null  # Key not found

# Verify .env file is being read
php artisan config:cache
php artisan config:clear
```

**Mode Switching Not Working**
```bash
# Check session configuration
php artisan session:table
php artisan migrate

# Verify middleware is registered
php artisan route:list | grep ai
```

**Streaming Issues**
```bash
# Check browser console for JavaScript errors
# Verify CORS headers if using external domains
# Test with different browsers for SSE compatibility
```

**Database Issues**
```bash
# Check PostgreSQL vector extension
psql your_db -c "SELECT * FROM pg_extension WHERE extname = 'vector';"

# Recreate database
php artisan migrate:fresh
php artisan db:seed
```

## 🤝 Contributing

We welcome contributions! Please follow these guidelines:

### Development Workflow
1. Fork the repository
2. Create feature branch: `git checkout -b feature/amazing-feature`
3. Make your changes with proper testing
4. Commit changes: `git commit -m 'Add amazing feature'`
5. Push to branch: `git push origin feature/amazing-feature`
6. Open Pull Request with detailed description

### Code Standards
- Follow PSR-12 coding standards
- Include tests for new features
- Update documentation for changes
- Use conventional commit messages
- Ensure all tests pass before PR

### Areas for Contribution
- 🆕 **New AI Features**: Additional AI capabilities
- 🎨 **UI Improvements**: Better user experience
- 📊 **Performance**: Optimization and caching
- 🧪 **Testing**: Additional test coverage
- 📖 **Documentation**: README and code comments

## 📝 License

This project is open-sourced software licensed under the **MIT License**. See [LICENSE](LICENSE) for full details.

## 🙏 Credits & Acknowledgments

- **Laravel Team**: For the amazing AI SDK and framework
- **Taylor Otwell**: For creating Laravel and developer-focused tools
- **Laravel Community**: For inspiration and best practices
- **AI Providers**: Gemini, OpenAI, ElevenLabs, and others

## 📞 Support & Community

- 📧 **Issues**: [GitHub Issues](https://github.com/yourusername/laravel-ai-showcase/issues)
- 💬 **Discussions**: [GitHub Discussions](https://github.com/yourusername/laravel-ai-showcase/discussions)
- 🐛 **Bug Reports**: Please include environment details and reproduction steps
- 💡 **Feature Requests**: Welcome and appreciated

## 🌐 About the Author

**Mohammad Ali Abd Al-Wahed** – Senior Software Engineer, Backend & AI Enthusiast  

- 🌐 Portfolio: [senior-mohammad-ali.vercel.app](https://senior-mohammad-ali.vercel.app/)  
- 💼 LinkedIn: [linkedin.com/in/mohammad-ali-abd-al-wahed](https://www.linkedin.com/in/mohammad-ali-abd-al-wahed)  
- 🐱 GitHub: [github.com/aliabdm](https://github.com/aliabdm)  
- 📝 Medium: [medium.com/@aliabdm](https://medium.com/@aliabdm)  
- 💻 Dev.to: [dev.to/maliano63717738](https://dev.to/maliano63717738)  


## 🌟 Project Status

### ✅ Production Ready
- [x] All tests passing (16/16)
- [x] Mock mode fully functional
- [x] Real mode with API integration
- [x] Streaming demo working
- [x] Mode switching implemented
- [x] Error handling comprehensive
- [x] Documentation complete
- [x] Security measures in place
- [x] Performance optimized

### 🚀 What This Demonstrates
1. **Professional Laravel Development**: Clean architecture, best practices
2. **AI SDK Integration**: Proper facade usage and error handling
3. **Modern Frontend**: Tailwind CSS, responsive design, JavaScript
4. **Real-time Features**: Server-Sent Events, streaming responses
5. **Production Thinking**: Security, validation, deployment considerations
6. **Developer Experience**: Clear documentation, easy setup

---

## 🎯 Quick Usage Summary

```bash
# 1. Clone and setup
git clone <repo>
cd laravel-ai-showcase
composer install
cp .env.example .env
php artisan key:generate

# 2. Try Mock Mode (no keys needed)
php artisan serve
# Visit http://localhost:8000
# All features work instantly!

# 3. Add API Keys for Real Mode
# Edit .env and add GEMINI_API_KEY
# Switch to Real Mode in UI
# Experience actual AI responses!

# 4. Test Streaming
# Visit http://localhost:8000/streaming/demo
# Watch AI responses appear character by character!
```

---

⭐ **If this project helps you understand Laravel AI SDK, please give it a star on GitHub!**

🚀 **Built with ❤️ using Laravel AI SDK**
