<?php

namespace AppModules\Billing\Concerns\Enums;

use App\Concerns\Traits\HasKeys;

enum BillingTransactionTypeEnum: string
{
    use HasKeys;

    case Credit = 'credit';
    case Debit = 'debit';
}
