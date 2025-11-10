<?php
// Define interface for calculation
interface PaymentMethod
{
    public function processPayment($amount);
    public function refund($amount);
}

// Define inerface for logs
interface Loggable
{
    public function log($message);
}

// Create credit card class
class CreditCard implements PaymentMethod, Loggable
{
    private $cardNumber;
    private $transactionId;

    public function __construct($cardNumber)
    {
        $this->cardNumber = $cardNumber;
    }

    public function processPayment($amount)
    {
        $this->transactionId = uniqid('txn_');
        $this->log("Платеж {$amount} обработан через кредитную карту. ID: {$this->transactionId}");
        return $this->transactionId;
    }

    public function refund($amount)
    {
        $this->log("Возврат {$amount} на карту {$this->maskCardNumber()}");
        return true;
    }

    public function log($message)
    {
        $timestamp = date('Y-m-d H:i:s');
        echo "[{$timestamp}] {$message}\n";
    }

    private function maskCardNumber()
    {
        return '****' . substr($this->cardNumber, -4);
    }
}

// Create class for PayPal
class PayPal implements PaymentMethod
{
    private $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function processPayment($amount)
    {
        return "Платеж {$amount} обработан через PayPal для {$this->email}";
    }

    public function refund($amount)
    {
        return "Возврат {$amount} на PayPal аккаунт {$this->email}";
    }
}

// Create class for payment
class PaymentService
{
    public function executePayment(PaymentMethod $paymentMethod, $amount)
    {
        return $paymentMethod->processPayment($amount);
    }
}

// Run execution
$paymentService = new PaymentService();

$creditCard = new CreditCard('1234567812345678');
$paypal     = new PayPal('user@example.com');

echo $paymentService->executePayment($creditCard, 100) . "\n";
echo $paymentService->executePayment($paypal, 200) . "\n";
