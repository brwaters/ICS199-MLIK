<?php
  require_once('./config.php');

  $token  = $_POST['stripeToken'];
  $email  = $_POST['stripeEmail'];

  $customer = \Stripe\Customer::create(array(
      'email' => $email,
      'source'  => $token
  ));

  $charge = \Stripe\Charge::create(array(
      'customer' => $customer->id,
      'amount'   => 5000,
      'currency' => 'cad'
  ));

  echo '<h1>Successfully charged $50.00!</h1>';
  echo '<a href="./index.php">Return to the main page.</a>';
?>
