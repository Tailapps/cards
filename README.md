# TailApps cards

The cards in the [TailApps](https://tailapps.com) Directory, as source. Each `.json` file here is one card: a plain JSON document that TailApps draws with its own native parts. Cards hold no code that downloads or runs at install time; anything a card can do is built into TailApps, and it asks you before a card gets a permission.

## Install a card

- **From TailApps on your computer:** open the Apps window, then **Sources › Add GitHub Repo…** and paste `https://github.com/Tailapps/cards`. Every card here is listed with **Install**. TailApps reads one commit at a time, and each card shows what it can do before it's installed.
- **From the Directory:** [tailapps.com/directory](https://tailapps.com/directory).
- **By hand:** download a `.json` file and open it with TailApps (or the Apps window's **Install from Folder…**).

## Make your own

Ask your agent to build one (TailApps has an MCP server and a [developer guide](https://tailapps.com/docs)), or copy a card here and change it. Your own repo of cards works as a source too: any public GitHub repo, or a folder in one, with `.json` or `.tailapp` files one level down.

## Cards

### Productivity

| Card | What it does |
| --- | --- |
| [To-do](todo.json) | A to-do list: add with Return, tick to finish, see what's left at a glance, and clear what's done. With Sync to My Devices on, it's the same on your Mac and iPhone. |
| [Notes](notes.json) | Quick notes with titles, colours and Markdown (lists, tick boxes, quotes). Click a note to edit it. On the Mac and iPhone. |
| [Reading List](reading-list.json) | Things to read, in two tabs. Swipe a row to mark it read or delete it (with Undo), right-click or long press to rename or file it, pull down on the iPhone to refresh. |
| [Habit](habits.json) | One habit: tick it off each time, see the last 13 weeks, and how long it's been. To share one history between your Mac and iPhone, turn on "Share ticks" in its settings and Sync to My Devices on both. |
| [Planner](planner.json) | A calendar you keep yourself: day, week, month, year and agenda views, tap a day or an hour to add an event, tap an event to delete it. On the Mac and iPhone. |
| [My Day](my-day.json) | Today's events with a Join button for meeting links, a countdown to the next one in the menu bar, a heads-up two minutes before each, and your open reminders to tick off or add to. |
| [Expenses](expenses.json) | Jot down what you spend and see the total, what's still to pay, the five biggest and a split by category. On the Mac and iPhone. |
| [Plants](plants.json) | When each plant was last watered. Tap one for its own screen, with Watered and Delete. Plants and History are tabs along the bottom on the iPhone and in the Mac's pop-out. |
| [World Clock](world-clock.json) | Your cities' times, days and working hours, who's there and how to say hello. Reorder them, slide a day ahead or back to plan a call. Same cities on all your devices. |
| [Timer](timer.json) | One countdown: type a length like 4:30 or 25, or tap a preset, then start. Name it and pick its colour in Settings. It rings even with TailApps closed, and with sync on it runs on all your devices. |
| [Pomodoro](pomodoro.json) | A focus timer with short and long breaks and a notification when each ends, on your Mac or iPhone. To share one timer, turn on "Share the timer" on each device, then Sync to My Devices. |

### Tools

| Card | What it does |
| --- | --- |
| [QR Code](qr-code.json) | Make a QR code for a link, a Wi-Fi network, a contact card or any text, right on this device, ready to scan with a phone camera. |
| [Scanner](scanner.json) | Scan QR codes and barcodes with the camera and keep the last twenty, ready to copy. On the iPhone and the Mac, with no script. |
| [Currency Converter](currency.json) | Convert an amount between 30 currencies with the European Central Bank's daily rates (free, no key): pick from and to, swap them, and see a few others at a glance. |
| [Encode & Decode](encoder.json) | Paste text to see it as Base64, URL-safe Base64, hex and percent-encoding, decode any of those back, or get its SHA-256, SHA-1, MD5 and SHA-512 hashes. Everything runs on this device. |
| [Summarise](summarise.json) | Paste any text and get a short summary and its to-dos from the AI model built into your Mac or iPhone, then translate the summary. Nothing leaves the device. Needs Apple Intelligence. |
| [Clipboard History](clipboard-history.json) | Keeps the last 50 things you copy on your Mac, text and pictures, to copy again with a click. Passwords are skipped, and it never syncs. On the iPhone, tap Paste to keep what you copied. |
| [File Tray](tray.json) | A shelf for files: drop them on it, then drag one or all of them out to Finder or any app on your Mac, or share them from your iPhone. Files stay a day. |
| [Folder Tidy](folder-tidy.json) | Lists a folder you choose, moves files out one at a time or all at once (a job you can stop, with a note when done), and puts a word in front of every name. Mac only. Never deletes. |

### Capture and media

| Card | What it does |
| --- | --- |
| [Recorder](recorder.json) | Record your screen, a window or an area, with your microphone, what your Mac plays and a round camera bubble. Watch it, then share it or upload it to your own bucket and copy the link. |
| [Clip](clip.json) | Record a short video, watch it, then share it or upload it to your own bucket (Cloudflare R2, S3 and others) and copy the link. Works on a Mac and on an iPhone. |
| [Quick Shot](quick-shot.json) | Grab a screen region, copy it and pull out the text; saves to a folder or a bucket, and can send each shot to your iPhone. |
| [Shot Editor](shot-editor.json) | Take a screenshot (or a photo on iPhone), mark it up with arrows, boxes and hidden parts, put it on a colourful background with padding, rounded corners and a shadow, then copy, share or upload it. |
| [Photo Drop](photo-drop.json) | Take a photo on your iPhone and upload it straight to your own bucket (Cloudflare R2, S3 and others), then copy its link. |
| [Image Convert](image-convert.json) | Drop images to convert them to JPG, PNG, HEIC or TIFF, optionally smaller, into a folder you choose. The originals stay as they are. |
| [Wallpaper](wallpaper.json) | Drop a picture to make it your desktop picture, on all screens or the main one, and switch back from a gallery. With Sync to My Devices on, pick or take a photo on your iPhone to set it. |
| [Dictate](dictate.json) | Hold a shortcut on the Mac (or tap Record on the iPhone), speak, let go: your words become text on your clipboard. Turn on Share and Sync to My Devices to see them on your other devices. |
| [Transcribe a File](transcribe-file.json) | Drop a voice memo or any audio file (on iPhone, tap to choose one) and get its text, made on this device. Copy it with one click. |

### Across devices

| Card | What it does |
| --- | --- |
| [Ping](ping.json) | Send a short note to Ping on your other devices: your iPhone gets an alert even with TailApps closed, your Mac a notification. Pictures sent to it show too. Turn on ⋯ › Sync to My Devices on each one. |
| [Counter](counter.json) | A shared tally: + and − on any of your devices. Turn on Sync to My Devices on each one and they stay in step. |
| [Device Messenger](device-messenger.json) | Type a message, choose how it arrives and which of your devices get it, then send. Each device shows who it came from. Turn on ⋯ › Sync to My Devices on each one. |
| [Where I Am](where-i-am.json) | Tap Share Where I Am on your iPhone (or Mac) and your other devices see the place on a map. One fix per tap, never in the background, with no script. |
| [Phone to Mac](phone-to-mac.json) | Send a photo or a file from your iPhone to your Mac: it lands in a folder you choose and shows in the card. |
| [Remote Recorder](remote-recorder.json) | Start and stop a screen recording on your Mac from your iPhone, see how long it's been going, then watch it on the phone. The Mac stays awake while it records. Turn on Sync to My Devices on both. |
| [Remote](remote.json) | iPhone buttons that run commands on your Mac (deploy to Vercel, git pull, git status) in one project folder; the Mac replies to that phone. Turn on Sync to My Devices on both. |

### Developer

| Card | What it does |
| --- | --- |
| [Claude sessions](claude-sessions.json) | Your running Claude Code sessions: folder, working, done or waiting on you, and for how long. Fed by Claude Code hooks on your Mac; with Sync to My Devices it nudges your iPhone too. |
| [Claude usage](claude-usage.json) | Your Claude plan's 5-hour and weekly limits, plus tokens and an estimated API cost by day, project and model, from Claude Code on your Mac. Syncs only these numbers to your iPhone. |
| [Claude Chat](claude-chat.json) | Chat with Claude using your own Anthropic API key; messages go to api.anthropic.com. Mac only until Connections (keys) come to the iPhone; the conversation stays on this device. |
| [CI run](ci-run.json) | The latest GitHub Actions run of a repo, job by job. Checks from your Mac with the GitHub CLI; for your iPhone too, turn on "Show on my other devices" there, then Sync to My Devices. |
| [Laravel Cloud](laravel-cloud.json) | Deploy status and a Deploy button for one project, from your Mac (cloud CLI). For status on your iPhone, turn on "Show on my other devices" there, then Sync to My Devices. |
| [Linear](linear.json) | The Linear issues assigned to you. Sign in with Linear on the card, no API key. Works on your Mac and your iPhone; each one signs in on its own. |
| [Ports](ports.json) | What is listening on this computer's TCP ports, with a Kill button. A tool for this computer only, so it doesn't sync. |
| [VM Board](vm-board.json) | Your VMPal VMs with Start, Stop, Open and a Peek screenshot, from your Mac. For your iPhone, turn on Show on my iPhone and Sync to My Devices: it starts, stops and peeks at VMs via your Mac. |
| [Debug Feed](debug-feed.json) | A live feed of what your code sends it: messages, values as JSON, SQL with timings and tables, labelled and coloured. Search, filter, pause, pin and copy. Mac only. Senders for curl and PHP/Laravel are in [debug-feed-senders](debug-feed-senders/). |

### System

| Card | What it does |
| --- | --- |
| [System Monitor](system-monitor.json) | CPU, GPU, memory and disk for this Mac, as gauges and a breakdown that update every 10 seconds. Mac only, with no script. |
| [System Controls](system-controls.json) | Sound, microphone, Wi-Fi, Bluetooth, network and power with a native control for every change, using the built-in system controls (no scripts). Mac only. |
| [Device Status](device-status.json) | This device's battery, connection and basics at a glance, on the Mac and iPhone alike, with no script. |
| [Keep Awake](keep-awake.json) | Keep this Mac (or the iPhone's screen) awake for a while or until you stop, with no script. The Mac lets go by itself when the time is up or TailApps quits. |
| [On Air](on-air.json) | On air in the menu bar while any app uses your mic or camera, the app in front, time away, and recent calls. It only knows that something listens, never what. Mac only. |

### Business

| Card | What it does |
| --- | --- |
| [Today](today.json) | Your shop's day at a glance: orders, revenue, visitors and the latest orders, pushed by your shop's server straight to your iPhone and Mac, even while the Mac sleeps. |

## License

MIT, see [LICENSE](LICENSE).
