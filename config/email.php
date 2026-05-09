<?php
// config/email.php
// Las credenciales de correo se leen del archivo .env

define('APP_EMAIL_FROM',      getenv('APP_EMAIL_FROM')      ?: 'no-reply@tu-dominio.com');
define('APP_EMAIL_FROM_NAME', getenv('APP_EMAIL_FROM_NAME') ?: 'EngineeringShop');
define('APP_EMAIL_BCC',       getenv('APP_EMAIL_BCC')       ?: '');
