# External Integrations & Package Ecosystem

## Package Integrations

### 1. `imrandevbd/laravel-ai-hub` (v1.7.1)
- Multi-provider gateway supporting OpenAI, Anthropic Claude, Google Gemini, Groq, and Ollama.
- Automatic failover if primary provider fails.
- Integrated into `App\Services\AiAssistantService` for return reason classification, fraud scoring, and customer query RAG.

### 2. `imrandevbd/laravel-unicode-pdf`
- Renders high-quality PDF documents with full UTF-8 internationalization.
- Embedded fonts supporting English, Bengali, and Arabic scripts.
- Used in `App\Services\PdfDocumentService` for return shipping labels, RMA authorization vouchers, and multilingual tax credit notes.

### 3. `imrandevbd/laravel-universal-slug`
- Internationalized, multilingual slug generation.
- Handles Latin, Bengali, Arabic, and Unicode titles gracefully for knowledge base and public resources.

### 4. `imrandevbd/laravel-ai-catalog`
- AI-assisted product catalog metadata enrichment, attribute extraction, and returnability tagging.

### 5. Architectural Adapters (`Drutogoti` & `AttendanceHub`)
Per instructions ("Document unavailable or incompatible packages. Provide built-in alternatives where reasonable. Use adapters for optional packages"):
- `App\Domain\Performance\DrutogotiPerformanceAdapter`: High-performance query caching and opcode optimization adapter.
- `App\Domain\Workforce\AttendanceHubAdapter`: Branch workforce scheduling, biometric attendance check-in, and operational workload distribution adapter.
