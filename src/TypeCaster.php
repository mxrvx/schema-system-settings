<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings;

use MXRVX\Schema\System\Settings\TypeCasters\ArrayCaster;
use MXRVX\Schema\System\Settings\TypeCasters\ArrayIntegerCaster;
use MXRVX\Schema\System\Settings\TypeCasters\ArrayStringCaster;
use MXRVX\Schema\System\Settings\TypeCasters\ArrayUniqueCaster;
use MXRVX\Schema\System\Settings\TypeCasters\BooleanCaster;
use MXRVX\Schema\System\Settings\TypeCasters\DateTimeCaster;
use MXRVX\Schema\System\Settings\TypeCasters\FloatCaster;
use MXRVX\Schema\System\Settings\TypeCasters\IntegerCaster;
use MXRVX\Schema\System\Settings\TypeCasters\JsonCaster;
use MXRVX\Schema\System\Settings\TypeCasters\ListCaster;
use MXRVX\Schema\System\Settings\TypeCasters\ListIntegerCaster;
use MXRVX\Schema\System\Settings\TypeCasters\ListStringCaster;
use MXRVX\Schema\System\Settings\TypeCasters\StringCaster;

/**
 * @phpstan-type class-string<TypeCasterInterface>
 */
class TypeCaster
{
    public const STRING = StringCaster::class;
    public const INTEGER = IntegerCaster::class;
    public const FLOAT = FloatCaster::class;
    public const BOOLEAN = BooleanCaster::class;
    public const DATETIME = DateTimeCaster::class;
    public const ARRAY = ArrayCaster::class;
    public const ARRAY_STRING = ArrayStringCaster::class;
    public const ARRAY_INTEGER = ArrayIntegerCaster::class;
    public const ARRAY_UNIQUE = ArrayUniqueCaster::class;
    public const JSON = JsonCaster::class;
    public const LIST = ListCaster::class;
    public const LIST_STRING = ListStringCaster::class;
    public const LIST_INTEGER = ListIntegerCaster::class;
}
