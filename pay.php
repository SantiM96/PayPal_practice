<?php

if(!isset($_POST['producto'], $_POST['precio'])) exit("Hubo un error");


use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;


require "config.php";



$product = htmlspecialchars($_POST['producto']);
$price = htmlspecialchars($_POST['precio']);
$price = (int) $price;  
$send = 0;
$total = $price + $send;


$payer = new Payer();
$payer->setPaymentMethod("paypal");


$item = new Item();
$item->setName($product)
    ->setCurrency('USD')
    ->setQuantity(1)
    ->setPrice($price);

//echo "Item" . "<hr>";    
//echo $item->getName() . "-----";
//echo $item->getCurrency() . "-----";
//echo $item->getQuantity() . "-----";
//echo $item->getPrice() . "<hr>";


$itemList = new ItemList();
$itemList->setItems(array($item));


$details = new Details();
$details->setShipping($send)
        ->setSubtotal($price);


//echo "Details" . "<hr>";  
//echo $details->getShipping() . "-----";
//echo $details->getSubtotal() . "<hr>";


$amount = new Amount();
$amount->setCurrency('USD')
        ->setTotal($total)
        ->setDetails($details);


//echo "Amount" . "<hr>";
//echo $amount->getCurrency() . "-----";
//echo $amount->getTotal() . "-----";
//echo $amount->getDetails() . "<hr>";


$transaction = new Transaction();
$transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription('Pago ')
            ->setInvoiceNumber(uniqid());


//echo "Transaction" . "<hr>";
//echo $transaction->getAmount() . "-----";
//echo $transaction->getItemList() . "-----";
//echo $transaction->getDescription() . "-----";
//echo $transaction->getInvoiceNumber() . "<hr>";


$redirect = new RedirectUrls();
$redirect->setReturnUrl(URL_SITE . "/payment_completed.php?success=true")
        ->setCancelUrl(URL_SITE . "/payment_completed.php?success=false");


//echo "RedirectUrls" . "<hr>";
//echo $redirect->getReturnUrl() . "-----";
//echo $redirect->getCancelUrl() . "<hr>";

$payment = new Payment();
$payment->setIntent("sale")
        ->setPayer($payer)
        ->setRedirectUrls($redirect)
        ->setTransactions(array($transaction));

//echo $payment->getTransactions();


try {
    $payment->create($apiContext);
}
catch(PayPal\Exception\PayPalConnectionException $pce) {
    echo "Hubo un problema al crear la conexión";
    echo "<pre>";
        print_r(json_decode($pce->getData()));
        exit;
    echo "</pre>";
}



$success = $payment->getApprovalLink();
header("Location: {$success}");
