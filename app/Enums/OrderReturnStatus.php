<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class OrderReturnStatus extends Enum
{
    const init =   0;
    const on_cargo =   1;
    const received = 2;
}
