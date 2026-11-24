# ZYPERNODES SMM HUB — EasySMMPanel API Integration Documentation

## Provider
EasySMMPanel API v2 — https://easysmmpanel.com/api/v2

## Authentication
All requests are **POST** to the base endpoint with form fields:
- `key` — API key (stored encrypted in `smm_providers.api_key`, never exposed to frontend)
- `action` — one of: `balance`, `services`, `add`, `status`, `refill`, `cancel`
- action-specific fields (e.g. `service`, `link`, `quantity` for `add`)

## Implementation class
`Paymenter\Extensions\Others\Smm\Services\EasySmmpanelApi`

Key methods:
- `request(string $action, array $data = [])` — POSTs to base URL with `key` + `action`, logs request/response to `smm_provider_logs`, returns `['success'=>bool, 'data'=>mixed, 'error'=>string|null]`.
- `testConnection()` — calls `balance` action.
- `balance()` — returns balance and updates `smm_providers.balance`.
- `services()` — returns the catalogue array.

## Provider field mapping (services response)
Each service object: `service` (id), `name`, `type`, `rate`, `min`, `max`, `dripfeed`, `refill`, `cancel`, `category`.
Mapped to `smm_services`: `provider_service_id`, `name`, `type`, `rate`, `min_quantity`, `max_quantity`, `provider_cost` (from `rate`), `provider_currency` (INR), `refill_eligible`, `cancel_eligible`, `drip_feed`, `parameters`, `raw_provider_data`.

## API actions used
| Action | Purpose | Request fields |
|--------|---------|----------------|
| balance | Provider balance check | key, action=balance |
| services | Import/sync catalogue | key, action=services |
| add | Place an order | key, action=add, service, link, quantity, (+dripfeed/runs/interval) |
| status | Check order status | key, action=status, order=<provider_order_id> |
| refill | Request refill | key, action=refill, order=<provider_order_id> |
| cancel | Request cancellation | key, action=cancel, order=<provider_order_id> |

## Security
- API key stored encrypted; never sent to frontend.
- Webhook (`POST /extensions/smm/webhook`) is CSRF-exempt and verifies HMAC-SHA256 signature (`X-Signature` / `X-Hmac-Signature`) using the API key material.
- All provider calls are queued jobs with retries and timeouts (30s).
- Server-side price calculation — client prices/quantities never trusted.

## Timeouts & retries
- HTTP connect/read timeout: 30 seconds.
- `SubmitSmmOrderJob`: 3 tries, 60s backoff.
- `SyncOrderStatusJob`: 2 tries.
- Failed submissions retain the paid order and are retryable safely (idempotent via `provider_order_id`).
