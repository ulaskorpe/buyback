<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CartItemStatus extends Enum
{
    const init =   0;
    const paid =   1;
    const cancelled = 2;
    const sent = 3;
    const completed = 4;
}
