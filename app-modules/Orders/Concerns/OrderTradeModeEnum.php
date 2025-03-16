<?php

namespace AppModules\Orders\Concerns;

enum OrderTradeModeEnum: string
{
    case Paper = 'paper';
    case Backtest = 'backtest';
    case Live = 'live';
}
