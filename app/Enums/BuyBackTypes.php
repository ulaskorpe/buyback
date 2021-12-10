<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class BuyBackTypes extends Enum
{
    const taken =   0;
    const control =   1;
    const preparing = 2;
    const completed = 3;
    const rejected = 4;
}
