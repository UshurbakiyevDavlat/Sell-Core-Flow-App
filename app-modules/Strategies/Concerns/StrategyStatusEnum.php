<?php

namespace AppModules\Strategies\Concerns;

enum StrategyStatusEnum: string
{
    case Pending = 'pending';
    case Success = 'success';
    case Failed = 'failed';
}
