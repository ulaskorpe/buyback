<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CustomerStatus extends Enum
{
    const init =   0;
    const active =   1;
    const inactive = 2;
    const banned = 3;
}
