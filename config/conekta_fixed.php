<?php
// config/conekta_fixed.php
// Las llaves se leen del archivo .env - nunca escribas credenciales aqui directamente.

if (!defined('CONEKTA_PRIVATE_KEY')) {
    define('CONEKTA_PRIVATE_KEY', getenv('CONEKTA_PRIVATE_KEY') ?: '');
}
if (!defined('CONEKTA_ACCEPT')) {
    define('CONEKTA_ACCEPT', 'application/vnd.conekta-v2.2.0+json');
}
