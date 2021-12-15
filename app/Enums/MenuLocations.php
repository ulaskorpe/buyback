<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class MenuLocations extends Enum
{
    const top =   1;
    const header =   2;
    const left = 3;

}
