<?php

declare(strict_types=1);

namespace Whirlwind\Domain\Money;

interface CurrencyInterface
{
    /**
     * @return string
     */
    public function getCode(): string;
}
