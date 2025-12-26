#!/usr/bin/env bash
set -euo pipefail

mkdir -p assets/libs

echo "Downloading bip39..."
if command -v curl >/dev/null 2>&1; then
  curl -L -o assets/libs/bip39.browser.js "https://unpkg.com/bip39@3.0.4/dist/bip39.browser.js"
else
  wget -O assets/libs/bip39.browser.js "https://unpkg.com/bip39@3.0.4/dist/bip39.browser.js"
fi

echo "Downloading bitcoinjs-lib..."
if command -v curl >/dev/null 2>&1; then
  curl -L -o assets/libs/bitcoinjs-lib.min.js "https://unpkg.com/bitcoinjs-lib@6.1.0/dist/bitcoinjs-lib.min.js"
else
  wget -O assets/libs/bitcoinjs-lib.min.js "https://unpkg.com/bitcoinjs-lib@6.1.0/dist/bitcoinjs-lib.min.js"
fi

echo "Done. Files saved to assets/libs/"
