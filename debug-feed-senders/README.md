# Sending to Debug Feed

[Debug Feed](../debug-feed.json) shows whatever your code posts to its inbox on your Mac. Install the card first, then pick a sender.

## From a shell

```sh
./debug-feed.sh "Deploy started" deploy blue
echo '{"value":{"order_id":1042},"label":"order"}' | ./debug-feed.sh -
```

It uses `tailapps-hook`, which ships inside TailApps and finds the port and the card's token by itself.

## With curl

The inbox listens on `127.0.0.1` only. Its port is in TailApps' `config.json`, the card's token in `inbox-tokens.json` (Settings › New token replaces it):

```sh
dir="$HOME/Library/Application Support/TailApps"
port=$(plutil -extract port raw "$dir/config.json")
token=$(plutil -extract 'debug-feed/log' raw "$dir/inbox-tokens.json")

curl -s "http://127.0.0.1:$port/inbox/debug-feed/log" \
  -H "Authorization: Bearer $token" -H 'Content-Type: application/json' \
  -d '{"type":"sql","sql":"select * from coupons limit 1","ms":0.84,"connection":"mysql","label":"Query"}'
```

## From PHP or Laravel

Copy [DebugFeed.php](DebugFeed.php) into your project (for example `app/Support/DebugFeed.php`, adding a namespace), then:

```php
DebugFeed::send('Checkout started for order #1042', 'checkout', 'green');
DebugFeed::send($order);                                   // arrays, models, collections, any object
DebugFeed::table(['subtotal' => '€129.00', 'discount' => '€64.50'], 'Totals', 'orange');
DebugFeed::sql('select * from coupons where code = ?', 0.84, 'mysql');
```

Every query, in a Laravel app's `AppServiceProvider::boot()`:

```php
if ($this->app->isLocal()) {
    DB::listen(fn ($query) => DebugFeed::sql($query->toRawSql(), $query->time, $query->connectionName));
}
```

It needs nothing else: no package, no config. It reads the port and token from TailApps' files (or `TAILAPPS_FEED_URL` and `TAILAPPS_FEED_TOKEN`), adds the file, line and project to each entry, and never throws. When the inbox says "too many" (it takes 10 posts a second), the rest wait and go as one batch at the end of the request (or a second later in a queue worker or Tinker). Set `DebugFeed::$enabled = false` to silence it.

## What the card takes

One JSON object per post (64 KB at most), or `{"type": "batch", "entries": [ … ]}` for up to 200 at once:

| Key | Meaning |
| --- | --- |
| `value` | Text, a number, a list or an object. Without it, the whole post is shown |
| `type` | `text`, `sql`, `table`, or leave it out for a value shown as JSON |
| `label`, `color` | A badge and its colour: `red`, `orange`, `yellow`, `green`, `blue`, `purple`, `gray` |
| `sql`, `ms`, `connection` | For `type: sql` |
| `rows` | For `type: table`: `[{"key": "subtotal", "value": "€129.00"}]` |
| `file`, `line`, `project` | Where it came from |
