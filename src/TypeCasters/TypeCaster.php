<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\TypeCasters;

use MXRVX\Schema\System\Settings\TypeCasterInterface;

abstract class TypeCaster implements TypeCasterInterface
{
    public static function isAllowed(mixed $value): bool
    {
        //NOTE "boolean", "integer", "double", "string", "array", "object", "resource", "NULL", "unknown type", "resource (closed)"
        return match (\gettype($value)) {
            'boolean','integer','double','string','array','object','NULL' => true,
            default => false,
        };
    }

    public static function cast(mixed $value, bool $skipEmpty = true): mixed
    {
        if (!self::isAllowed($value)) {
            return null;
        }

        try {
            /** @var callable[] $rules */
            foreach (static::getTransformers() as $rule) {
                if (\is_callable($rule, true)) {
                    /** @var mixed $value */
                    $value = $rule($value, $skipEmpty);
                    if ($value === null) {
                        break;
                    }
                }
            }
        } catch (\Throwable $e) {
            throw new \Exception(
                \sprintf('Unable to typecast the `%s` field. %s', \var_export($value, true), $e->getMessage()),
            );
        }

        return $value;
    }

    /**
     * @return callable[]
     */
    abstract public static function getTransformers(): array;

    public function __invoke(): void {}
}
