<?php

declare(strict_types=1);

// Long-running MCP server. Embr will keep this process alive.
// Health check is handled separately (see public/health.php served by built-in PHP for /health).
// Actually — Embr's per-request PHP model won't serve a side health on the same port,
// so this server exposes /health itself in addition to /mcp.

chdir(__DIR__);
require_once __DIR__ . '/vendor/autoload.php';

use PhpMcp\Server\Server;
use PhpMcp\Server\Transports\StreamableHttpServerTransport;
use Psr\Log\AbstractLogger;

class StderrLogger extends AbstractLogger
{
    public function log($level, \Stringable|string $message, array $context = []): void
    {
        fwrite(STDERR, sprintf(
            "[%s] %s %s\n",
            strtoupper($level),
            $message,
            empty($context) ? '' : json_encode($context)
        ));
    }
}

$port = (int) (getenv('PORT') ?: '8000');
$logger = new StderrLogger();
$logger->info("Starting MCP server on 0.0.0.0:$port");

try {
    $server = Server::make()
        ->withServerInfo('Embr MCP PHP Sample', '0.1.0')
        ->withLogger($logger)
        ->build();

    $server->discover(__DIR__, ['src']);

    // Bind on 0.0.0.0 for Embr; mount MCP at /mcp
    $transport = new StreamableHttpServerTransport('0.0.0.0', $port, 'mcp');

    $server->listen($transport);
} catch (\Throwable $e) {
    fwrite(STDERR, "[FATAL] " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n");
    exit(1);
}
