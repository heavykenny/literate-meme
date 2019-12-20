<?php
$autoloader = require 'vendor/autoload.php';

use Kenny\Payment\MonnifyClass;

$userName = "MK_TEST_WD7TZCMQV7";
$token = "H5EQMQSHSURJNQ7UH2R78YAH6UN54ZP7";
$contract = "2957982769";

$endpoint = new MonnifyClass($userName, $token, $contract);

$authToken = $endpoint->authenticator();