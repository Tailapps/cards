<?php

/**
 * Send values from your PHP code to the TailApps Debug Feed card on this Mac.
 *
 *   DebugFeed::send($order);                          // any value: text, array, object
 *   DebugFeed::send('Checkout started', 'checkout', 'green');
 *   DebugFeed::table(['subtotal' => '€129.00', 'discount' => '€64.50'], 'Totals', 'orange');
 *   DebugFeed::sql('select * from coupons where code = ?', 0.84, 'mysql');
 *
 * It posts to TailApps' local inbox (127.0.0.1 only) and reads the port and the card's token from TailApps' own
 * files, so there's nothing to configure. It never throws: when TailApps isn't running, calls do nothing.
 * Set TAILAPPS_FEED_URL and TAILAPPS_FEED_TOKEN to override (for a Linux computer, or a container).
 *
 * Don't ship it to production: it's a development tool, and does nothing there unless your code calls it.
 */
final class DebugFeed
{
    /** Shown on every entry; defaults to the project folder's name. */
    public static ?string $project = null;

    /** Set false to turn every call into a no-op (e.g. from config). */
    public static bool $enabled = true;

    /** Entries the inbox turned away (more than 10 posts a second): sent together, as one batch, at the end. */
    private static array $waiting = [];

    private static float $waitingSince = 0;

    private static ?array $target = null;

    private static bool $flushOnShutdown = false;

    public static function send(mixed $value, ?string $label = null, ?string $color = null): void
    {
        self::post([
            'type' => is_string($value) ? 'text' : 'value',
            'value' => self::plain($value),
            'label' => $label,
            'color' => $color,
        ]);
    }

    /** Key/value rows, in your order (the card can't keep an object's order). */
    public static function table(array $rows, ?string $label = null, ?string $color = null): void
    {
        $list = [];
        foreach ($rows as $key => $value) {
            $list[] = ['key' => (string) $key, 'value' => self::plain($value)];
        }

        self::post(['type' => 'table', 'rows' => $list, 'label' => $label, 'color' => $color]);
    }

    public static function sql(string $sql, ?float $ms = null, ?string $connection = null, string $label = 'Query', ?string $color = null): void
    {
        self::post(['type' => 'sql', 'sql' => $sql, 'ms' => $ms, 'connection' => $connection, 'label' => $label, 'color' => $color]);
    }

    /** Sends whatever is still waiting, in batches. Runs by itself when the script ends. */
    public static function flush(): void
    {
        $tries = 0;
        while (self::$waiting !== [] && $tries < 5) {
            $status = self::deliver(['type' => 'batch', 'entries' => array_slice(self::$waiting, 0, 100)]);
            if ($status === 429) {
                $tries++;
                usleep(1_100_000);

                continue;
            }
            if ($status === false) {
                break;
            }
            array_splice(self::$waiting, 0, 100);
        }
        self::$waiting = [];
    }

    private static function post(array $entry): void
    {
        if (! self::$enabled) {
            return;
        }

        $entry += self::origin();
        $entry = array_filter($entry, fn ($v) => $v !== null);

        // Once the inbox has said "too many", entries wait and go as one batch: at the end, or after a second in a
        // long-running process (a queue worker, Tinker).
        if (self::$waiting !== []) {
            self::$waiting[] = $entry;
            if (microtime(true) - self::$waitingSince > 1.0) {
                self::flush();
            }

            return;
        }

        if (self::deliver($entry) === 429) {
            self::$waiting[] = $entry;
            self::$waitingSince = microtime(true);
            if (! self::$flushOnShutdown) {
                self::$flushOnShutdown = true;
                register_shutdown_function([self::class, 'flush']);
            }
        }
    }

    /** Posts one body. Returns the HTTP status, or false when TailApps couldn't be reached. */
    private static function deliver(array $body): int|false
    {
        $target = self::target();
        if ($target === null) {
            return false;
        }

        $json = json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
        if ($json === false) {
            return false;
        }
        if (strlen($json) > 60_000) {
            $json = json_encode(['type' => 'text', 'value' => 'Too big for the feed: '.strlen($json).' bytes (64 KB max). '.substr($json, 0, 2_000).'…'] + self::origin(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        }

        $context = stream_context_create(['http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\nAuthorization: Bearer {$target['token']}\r\n",
            'content' => $json,
            'timeout' => 0.5,
            'ignore_errors' => true,
        ]]);

        @file_get_contents($target['url'], false, $context);
        $headers = function_exists('http_get_last_response_headers')
            ? (http_get_last_response_headers() ?? [])
            : ($http_response_header ?? []);

        return preg_match('{^HTTP/\S+ (\d{3})}', $headers[0] ?? '', $m) ? (int) $m[1] : false;
    }

    /** The local inbox's URL and token, from the environment or TailApps' files; null when there isn't one. */
    private static function target(): ?array
    {
        if (self::$target !== null) {
            return self::$target ?: null;
        }

        $url = getenv('TAILAPPS_FEED_URL') ?: null;
        $token = getenv('TAILAPPS_FEED_TOKEN') ?: null;

        if ($url === null || $token === null) {
            $home = getenv('HOME') ?: (function_exists('posix_getpwuid') ? (posix_getpwuid(posix_geteuid())['dir'] ?? '') : '');
            $folder = $home.'/Library/Application Support/TailApps';
            $config = json_decode((string) @file_get_contents($folder.'/config.json'), true);
            $tokens = json_decode((string) @file_get_contents($folder.'/inbox-tokens.json'), true);
            $url ??= isset($config['port']) ? "http://127.0.0.1:{$config['port']}/inbox/debug-feed/log" : null;
            $token ??= $tokens['debug-feed/log'] ?? null;
        }

        self::$target = ($url && $token) ? ['url' => $url, 'token' => $token] : [];

        return self::$target ?: null;
    }

    /** file:line of the caller, and the project. */
    private static function origin(): array
    {
        $file = null;
        foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS) as $frame) {
            if (isset($frame['file']) && $frame['file'] !== __FILE__) {
                $file = $frame;
                break;
            }
        }

        self::$project ??= basename(function_exists('base_path') ? base_path() : (getcwd() ?: ''));

        return [
            'file' => $file ? basename($file['file']) : null,
            'line' => $file['line'] ?? null,
            'project' => self::$project ?: null,
        ];
    }

    /** A value as plain JSON data: Laravel models and collections via toArray, other objects by their properties. */
    private static function plain(mixed $value, int $depth = 0): mixed
    {
        if ($depth > 8) {
            return '…';
        }
        if ($value instanceof \JsonSerializable) {
            $value = $value->jsonSerialize();
        } elseif (is_object($value) && method_exists($value, 'toArray')) {
            $value = $value->toArray();
        } elseif ($value instanceof \Throwable) {
            return ['exception' => $value::class, 'message' => $value->getMessage(), 'at' => basename($value->getFile()).':'.$value->getLine()];
        } elseif ($value instanceof \DateTimeInterface) {
            return $value->format(DATE_ATOM);
        } elseif (is_object($value)) {
            $value = ['class' => $value::class] + get_object_vars($value);
        } elseif (is_resource($value)) {
            return 'resource('.get_resource_type($value).')';
        } elseif (is_float($value) && ! is_finite($value)) {
            return (string) $value;
        }

        if (is_array($value)) {
            return array_map(fn ($v) => self::plain($v, $depth + 1), $value);
        }

        return $value;
    }
}
