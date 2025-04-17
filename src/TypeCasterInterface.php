<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings;

interface TypeCasterInterface
{
    public static function cast(mixed $value, bool $skipEmpty): mixed;

    /**
     * @return callable[]
     */
    public static function getTransformers(): array;
}
