<?php

namespace AppModules\Assets\Concerns\Enums;

use App\Concerns\Traits\HasKeys;

enum AssetTypeEnum: string
{
    use HasKeys;

    case Stock = 'stock';             // Акции
    case Crypto = 'crypto';           // Криптовалюта
    case Forex = 'forex';             // Валютные пары (Forex)
    case Bond = 'bond';               // Облигации
    case Commodity = 'commodity';     // Товары (нефть, золото, сельхозпродукция)
    case RealEstate = 'real_estate';  // Недвижимость
    case Cash = 'cash';               // Денежные средства и их эквиваленты
    case Derivative = 'derivative';   // Производные финансовые инструменты (опционы, фьючерсы)
    case ETF = 'etf';                 // Биржевые фонды (ETF)
    case Index = 'index';             // Фондовые индексы (например, S&P 500)
    case Intangible = 'intangible';   // Нематериальные активы (патенты, бренды)
}
