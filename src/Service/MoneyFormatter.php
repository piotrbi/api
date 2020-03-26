<?php

namespace App\Service;

use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use NumberFormatter;

class MoneyFormatter
{
    const LOCALE = 'pl_PL';

    /** @var IntlMoneyFormatter */
    public static $instance = null;

    public static function make(): IntlMoneyFormatter
    {
        if (is_null(self::$instance)) {
            self::$instance = new IntlMoneyFormatter(
                new NumberFormatter(self::LOCALE, NumberFormatter::CURRENCY),
                new ISOCurrencies()
            );
        }

        return self::$instance;
    }

    public static function pln(int $value): string
    {
        return self::make()->format(Money::PLN($value));
    }
}
