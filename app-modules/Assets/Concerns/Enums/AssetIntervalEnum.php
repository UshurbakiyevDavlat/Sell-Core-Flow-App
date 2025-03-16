<?php

namespace AppModules\Assets\Concerns\Enums;

enum AssetIntervalEnum: string
{
    case Daily = 'daily';
    case Weekly = 'weekly';
    case Monthly = 'monthly';
}
