<?php
  session_start();
  include 'functions.php';
  require_once('./config.php');
  $cust_id = $_SESSION['cust_id'];
  $token  = $_POST['stripeToken'];
  $email  = $_POST['stripeEmail'];
  $total_amount = $_POST['sub_total'];
  $stripe_amount = $_POST['sub_total'] * 100;

  $customer = \Stripe\Customer::create(array(
      'email' => $email,
      'source'  => $token
  ));

  $charge = \Stripe\Charge::create(array(
      'customer' => $customer->id,
      'amount'   => $stripe_amount,
      'currency' => 'cad'
  ));

  //echo '<h1>Successfully charged ' . number_format((float) $total_amount, 2, '.', '') . '</h1>';
  //echo '<a href="./index.php">Return to the main page.</a>';
  echo $cust_id;
  makeOrder( $cust_id );
  //echo '<script>'
  //. 'window.location.replace("index.php");'
  //. 'alert("You\'re order has been complete! You have been billed for:  '
  //. number_format((float) $total_amount, 2, '.', '')
  //. '");'
  //. '</script>';
?>
