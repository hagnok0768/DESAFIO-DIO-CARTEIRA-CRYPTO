// wallet.js
// Geração client-side de carteira HD (BIP39 + BIP84)
// Requisitos: incluir bip39 e bitcoinjs-lib no index.php

(function(){
	// Expor função global para geração
	window.generateWallet = async function(onUpdate) {
		// If bip39 wasn't loaded by the page, try to load a known CDN fallback.
		if (typeof bip39 === 'undefined') {
			// attempt dynamic load from multiple CDNs with timeout
			const cdns = [
				'https://cdn.jsdelivr.net/npm/bip39@3.0.4/dist/bip39.browser.js',
				'https://unpkg.com/bip39@3.0.4/dist/bip39.browser.js',
				'https://cdnjs.cloudflare.com/ajax/libs/bip39/3.0.4/bip39.browser.min.js'
			];
			const loadScript = (url, ms) => new Promise((resolve, reject) => {
				const s = document.createElement('script');
				let done = false;
				s.src = url;
				s.async = true;
				s.onload = () => { if (!done) { done = true; resolve(); } };
				s.onerror = () => { if (!done) { done = true; reject(new Error('failed to load ' + url)); } };
				document.head.appendChild(s);
				// fallback timeout
				setTimeout(() => { if (!done) { done = true; reject(new Error('timeout loading ' + url)); } }, ms || 8000);
			});
			let loaded = false;
			for (const url of cdns) {
				try {
					await loadScript(url, 8000);
					if (typeof bip39 !== 'undefined') { loaded = true; break; }
				} catch (err) {
					console.warn('bip39 load attempt failed:', url, err.message);
				}
			}
			if (!loaded && typeof bip39 === 'undefined') {
				console.error('bip39 dynamic load failed for all CDNs');
				throw new Error('bip39 not loaded');
			}
		}

		// detect bitcoinjs lib namespace (variações entre builds)
		const bjs = window.bitcoinjs || window.bitcoinjsLib || window['bitcoinjs-lib'] || window.bitcoin;
		if (!bjs || !bjs.bip32) throw new Error('bitcoinjs-lib not loaded or unexpected bundle');

		const mnemonic = bip39.generateMnemonic();
		const seed = await bip39.mnemonicToSeed(mnemonic);

		const root = bjs.bip32.fromSeed(seed);
		const path = "m/84'/0'/0'/0/0"; // BIP84 native segwit
		const child = root.derivePath(path);
		const p2wpkh = bjs.payments.p2wpkh({ pubkey: child.publicKey });
		const address = p2wpkh.address;
		const wif = (typeof child.toWIF === 'function') ? child.toWIF() : '';
		const result = { mnemonic, wif, address };
		if (typeof onUpdate === 'function') onUpdate(result);
		return result;
	};
})();
