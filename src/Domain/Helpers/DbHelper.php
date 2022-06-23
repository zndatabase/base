<?php

namespace ZnDatabase\Base\Domain\Helpers;

use ZnCore\Base\Libs\Arr\Helpers\ArrayHelper;
use ZnCore\Base\Libs\DotEnv\Domain\Libs\DotEnv;
use ZnDatabase\Base\Domain\Enums\DbDriverEnum;

class DbHelper
{

    public static function encodeDirection($direction)
    {
        $directions = [
            SORT_ASC => 'asc',
            SORT_DESC => 'desc',
        ];
        return $directions[$direction];
    }

}