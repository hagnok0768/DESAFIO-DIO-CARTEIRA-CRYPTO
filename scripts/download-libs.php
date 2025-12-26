<?php
// Simple PHP downloader for environments without shell access.
// Usage: php scripts/download-libs.php
$files = [
    'https://unpkg.com/bip39@3.0.4/dist/bip39.browser.js' => __DIR__ . '/../assets/libs/bip39.browser.js',
    'https://unpkg.com/bitcoinjs-lib@6.1.0/dist/bitcoinjs-lib.min.js' => __DIR__ . '/../assets/libs/bitcoinjs-lib.min.js',
];

@mkdir(__DIR__ . '/../assets/libs', 0755, true);
foreach ($files as $url => $path) {
    echo "Downloading $url...\n";
    $ctx = stream_context_create(['http' => ['timeout' => 30]]);
    $data = @file_get_contents($url, false, $ctx);
    if ($data === false) {
        echo "Failed to download $url\n";
        continue;
    }
    file_put_contents($path, $data);
    echo "Saved to $path\n";
}

echo "Done.\n";
