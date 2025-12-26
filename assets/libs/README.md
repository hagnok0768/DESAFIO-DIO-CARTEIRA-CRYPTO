Place dependency files here if your host blocks CDN access.

Recommended files to download and put in this folder:

1) bip39.browser.js (v3.0.4)
   https://unpkg.com/bip39@3.0.4/dist/bip39.browser.js

2) bitcoinjs-lib (minified)
   https://unpkg.com/bitcoinjs-lib@6.1.0/dist/bitcoinjs-lib.min.js

How to download (example using curl):

curl -L -o assets/libs/bip39.browser.js "https://unpkg.com/bip39@3.0.4/dist/bip39.browser.js"
curl -L -o assets/libs/bitcoinjs-lib.min.js "https://unpkg.com/bitcoinjs-lib@6.1.0/dist/bitcoinjs-lib.min.js"

After placing those files, reload the page — the loader in index.php prefers local files first.

Automated options:

- Shell (Linux/macOS) — run from project root:

   chmod +x scripts/download-libs.sh
   ./scripts/download-libs.sh

- PHP (when you have PHP CLI or via hosting file manager):

   php scripts/download-libs.php

If your host blocks outbound HTTP requests, download the two files locally and upload them to `assets/libs/` via FTP or the Hostinger file manager.
