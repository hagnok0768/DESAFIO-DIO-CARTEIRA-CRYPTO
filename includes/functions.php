<?php
// Helpers para formatação de saldo, etc
function format_btc($amount) {
    return number_format($amount, 8) . ' BTC';
}
