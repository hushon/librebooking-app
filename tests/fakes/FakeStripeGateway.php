<?php

require_once(ROOT_DIR . 'Domain/PaymentGateway.php');


class FakeStripeGateway extends StripeGateway
{
    public $_Token;
    public $_Cart;
    public $_Email;
    public $_ChargeResponse = false;
    public $_CreateSessionCart;
    public $_SuccessUrl;
    public $_CancelUrl;
    public $_SessionEmail;
    public $_Session;
    public $_CompleteSessionId;
    public $_CompleteResult = false;

    public function __construct()
    {
        parent::__construct(true, '', '');
    }

    public function CreateCheckoutSession(CreditCartSession $cart, string $successUrl, string $cancelUrl, string $email)
    {
        $this->_CreateSessionCart = $cart;
        $this->_SuccessUrl = $successUrl;
        $this->_CancelUrl = $cancelUrl;
        $this->_SessionEmail = $email;

        return $this->_Session ?? (object)['id' => 'sess_test'];
    }

    public function CompleteCheckoutSession(string $sessionId, IPaymentTransactionLogger $logger)
    {
        $this->_CompleteSessionId = $sessionId;
        $logger->LogPayment('', '', '', '', 0, 0, '', '', '', Date::Now(), '', '', '');
        return $this->_CompleteResult;
    }

    public function Charge(CreditCartSession $cart, $email, $token, IPaymentTransactionLogger $logger)
    {
        $this->_Cart = $cart;
        $this->_Email = $email;
        $this->_Token = $token;

        return $this->_ChargeResponse;
    }

    public function Refund(TransactionLogView $log, $amount, IPaymentTransactionLogger $logger)
    {
        $this->_LastTransactionView = $log;
        $this->_LastRefundAmount = $amount;
        return $this->_Refunded;
    }
}
