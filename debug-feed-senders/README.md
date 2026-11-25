# Sending to Debug Feed

[Debug Feed](../debug-feed.json) shows whatever your code posts to its inbox on your Mac. Install the card first, then pick a sender.

## Any language, no token

The card listens on its own port, `47312`, on this Mac only. Post JSON to it from anything that can make an HTTP request:

```sh
curl -d '{"value":"hi","label":"test"}' 127.0.0.1:47312
```

Use `127.0.0.1` rather than `localhost`, which some clients look up as IPv6 first. Only programs on your Mac can reach it: web pages are refused.

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
