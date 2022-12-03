<?php

namespace Acelle\Library\Contracts;

use Acelle\Model\Invoice;
use Acelle\Model\Transaction;
use Acelle\Library\TransactionVerificationResult;

interface PaymentGatewayInterface
{
    public function getName(): string;
    public function getType(): string;
    public function getDescription(): string;
    public function isActive(): bool;
    public function getSettingsUrl(): string;
    public function getCheckoutUrl(Invoice $invoice): string;

    // auto
    public function supportsAutoBilling(): bool;
    public function autoCharge(Invoice $invoice); // dành cho cronjob của core gọi
    public function getAutoBillingDataUpdateUrl(): string;
    public function verify(Transaction $transaction): TransactionVerificationResult;

    //
    public function allowManualReviewingOfTransaction(): bool;
    public function getMinimumChargeAmount($currency);
}
