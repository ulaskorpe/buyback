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
    const sent = 2;
    const cancelled = 3;
    const completed = 4;


}
