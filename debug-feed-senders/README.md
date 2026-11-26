# Sending to Debug Feed

[Debug Feed](../debug-feed.json) shows whatever your code posts to its inbox on your Mac. Install the card first, then pick a sender.

## Any language, no token

The card listens on its own port, `47312`, on this Mac only. Post JSON to it from anything that can make an HTTP request:

```sh
curl -d '{"value":"hi","label":"test"}' 127.0.0.1:47312
```

Use `127.0.0.1` rather than `localhost`, which some clients look up as IPv6 first. Only programs on your Mac can reach it: web pages are refused.

### Check it's TailApps before you send anything private

While TailApps is off, another user on the same Mac could open port 47312 and read what you post. That's fine for "hi", not for SQL with customer data. A sender that cares asks the port to prove it's TailApps first: send a fresh random nonce to `GET /proof`, and TailApps answers with an HMAC of it keyed by the card's token, which only TailApps and you can read.

```python
import hashlib, hmac, json, os, secrets, urllib.request

tokens = os.path.expanduser("~/Library/Application Support/TailApps/inbox-tokens.json")
token = json.load(open(tokens))["debug-feed/log"]
nonce = secrets.token_hex(32)
direct = urllib.request.build_opener(urllib.request.ProxyHandler({}))  # loopback only, never a proxy
request = urllib.request.Request("http://127.0.0.1:47312/proof", headers={"X-TailApps-Nonce": nonce})
proof = json.load(direct.open(request, timeout=1))["proof"]
expected = hmac.new(token.encode(), b"tailapps-loopback-v1\n" + nonce.encode(), hashlib.sha256).hexdigest()
if hmac.compare_digest(proof, expected):
    pass  # it's TailApps: post to 127.0.0.1:47312 now. Check again before each private post
```

Always check and post over `127.0.0.1`, never `localhost`. Any language works the same way: 32 random bytes as 64 lower-case hex in `X-TailApps-Nonce`, then compare `proof` with HMAC-SHA256 of `tailapps-loopback-v1`, a newline and the nonce, keyed by the token. Don't compute it with `openssl dgst -hmac <token>` in a shell: other users can see command-line arguments. From a shell, use `debug-feed.sh` below, which checks for you.

## From a shell

```sh
./debug-feed.sh "Deploy started" deploy blue
echo '{"value":{"order_id":1042},"label":"order"}' | ./debug-feed.sh -
```

It uses `tailapps-hook`, which ships inside TailApps, finds the port and the card's token by itself, and only sends once it has checked that TailApps is the one listening.

## With curl

The inbox listens on `127.0.0.1` only. Its port is in TailApps' `config.json`, the card's token in `inbox-tokens.json` (Settings › New token replaces it). This sends the card's token without checking who listens, so prefer `debug-feed.sh` on a Mac you share with other users:

```sh
dir="$HOME/Library/Application Support/TailApps"
port=$(plutil -extract port raw "$dir/config.json")
token=$(plutil -extract 'debug-feed/log' raw "$dir/inbox-tokens.json")

# The header goes in on stdin, so the token never shows in the process list.
printf 'Authorization: Bearer %s\n' "$token" | curl -s "http://127.0.0.1:$port/inbox/debug-feed/log" \
  -H @- -H 'Content-Type: application/json' \
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
