# Environment & Application Configuration

## Key Environment Variables

| Variable | Description | Default | Example |
|---|---|---|---|
| `APP_NAME` | Name of the platform | Returns & Refund Manager | `Returns & Refund Manager` |
| `APP_ENV` | Application environment | `local` | `production` |
| `APP_KEY` | 32-character encryption key | - | `base64:...` |
| `APP_URL` | Root domain URL | `http://localhost` | `https://rma.example.com` |
| `DB_CONNECTION` | Primary database driver | `sqlite` | `mysql` / `pgsql` |
| `DB_HOST` | Database host | `127.0.0.1` | `127.0.0.1` |
| `DB_PORT` | Database port | `3306` | `3306` |
| `DB_DATABASE` | Database name | `database.sqlite` | `rma_saas` |
| `CACHE_STORE` | Cache driver | `file` / `array` | `redis` |
| `QUEUE_CONNECTION` | Background queue driver | `sync` | `redis` |
| `SESSION_DRIVER` | Session store driver | `file` | `redis` |

## AI Hub Configuration (`laravel-ai-hub`)
```env
AI_HUB_DEFAULT_PROVIDER=openai
AI_HUB_FAILOVER_PROVIDERS=anthropic,gemini
OPENAI_API_KEY=sk-...
ANTHROPIC_API_KEY=sk-ant-...
GEMINI_API_KEY=AIza...
```
*Note: If no AI API keys are configured, the application automatically engages high-speed heuristic fallback classifiers without throwing exceptions.*

## Performance Optimization Configuration
```env
DRUTOGOTI_ENABLED=true
DRUTOGOTI_CACHE_TTL=3600
DRUTOGOTI_QUERY_CACHE=true
```
