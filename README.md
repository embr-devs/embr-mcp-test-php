# embr-mcp-test-php

Minimal PHP MCP server for Embr platform retest (June 2026).

Uses `php-mcp/server` (ReactPHP under the hood) to expose two tools
(`get_weather`, `get_time`) over MCP streamable-HTTP at `/mcp`.

Runs as a long-lived process via `php server.php` rather than the per-request
PHP-FPM/Apache model that's typical of PHP hosting. This is required because
MCP streamable-HTTP needs persistent in-memory session state across requests.

Companion samples: `embr-foundry-tool-sample(-node|-dotnet)` and
`embr-mcp-test-go` cover other languages.
