// wallet.js
// Geração client-side de carteira HD (BIP39 + BIP84)
// Requisitos: incluir bip39 e bitcoinjs-lib no index.php

(function(){
	// Expor função global para geração
	window.generateWallet = async function(onUpdate) {
		const mnemonic = bip39.generateMnemonic();
		const seed = await bip39.mnemonicToSeed(mnemonic);
		const root = bitcoinjs.bip32.fromSeed(seed);
		const path = "m/84'/0'/0'/0/0"; // BIP84 native segwit
		const child = root.derivePath(path);
		const p2wpkh = bitcoinjs.payments.p2wpkh({ pubkey: child.publicKey });
		const address = p2wpkh.address;
		const wif = child.toWIF();
		const result = { mnemonic, wif, address };
		if (typeof onUpdate === 'function') onUpdate(result);
		return result;
	};
})();
