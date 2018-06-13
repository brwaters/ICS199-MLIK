<?php
require_once('vendor/autoload.php');

$stripe = array(
  "secret_key"      => "sk_test_5Iw1oj66ffUgUAmkwx6CQfCV",
  "publishable_key" => "pk_test_DmdZid3rTfYszh9lLM9c0786"
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);
?>
