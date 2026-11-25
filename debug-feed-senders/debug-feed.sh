#!/bin/sh
# Send one entry to the Debug Feed card on this Mac.
#   debug-feed.sh "Checkout started" [label] [color]
#   echo '{"value":{"order_id":1042},"label":"order"}' | debug-feed.sh -
# Uses tailapps-hook, which finds TailApps' local port and the card's token itself. It never fails your script.
hook="$HOME/Applications/TailApps.app/Contents/MacOS/tailapps-hook"
[ -x "$hook" ] || hook="/Applications/TailApps.app/Contents/MacOS/tailapps-hook"
[ -x "$hook" ] || exit 0

if [ "$1" = "-" ]; then
  exec "$hook" debug-feed/log
fi

# Plain text in, JSON out: python3 is on every Mac with the command line tools; without it, send the text as is.
if command -v python3 >/dev/null 2>&1; then
  python3 -c 'import json,sys; print(json.dumps({"type":"text","value":sys.argv[1],"label":sys.argv[2] or None,"color":sys.argv[3] or None,"project":sys.argv[4]}))' \
    "$1" "${2:-}" "${3:-}" "$(basename "$PWD")" | "$hook" debug-feed/log
else
  printf '%s' "$1" | "$hook" debug-feed/log
fi
