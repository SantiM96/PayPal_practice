<?php

require 'paypal/autoload.php';

define('URL_SITE', 'http://localhost:81/paypal_practice');

$apiContext = new \PayPal\Rest\ApiContext (
    new \PayPal\Auth\OAuthTokenCredential(
        'AcBWEGaRZnVnml3am4ymZz1ZcOx18XLZYMDDiNO9lvDXqA3ecae-v2-R0lk8SRVG4kAHa8LAmHY-FFep', //ClientID
        'EN_ADRns5CMvpJi7snDyK1fkhz6btjB8UiO5TGvsVSqxuU-Ocn8LXTJUZwvL0etMFYWcRHcC7wYs72-p'  //Secret
    )
);
