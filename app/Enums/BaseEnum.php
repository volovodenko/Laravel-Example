<?php

namespace App\Enums;

use App\Enums\Contracts\Enum;
use App\Exceptions\EnumException;

abstract class BaseEnum implements Enum
{
    protected int | string $value;

    protected static array $constCache = [];

    public function __construct(self | int | string $value)
    {
        if ($value instanceof static) {
            $value = $value->value();
        }

        if (!self::isValidValue($value)) {
            throw new EnumException("Value '{$value}' is not part of the enum " . static::class);
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value();
    }

    public static function __callStatic(string $key, array $arguments)
    {
        $constants = static::toArray();

        if (self::isValidKey($key)) {
            return new static($constants[$key]);
        }

        throw new EnumException("No enum constant '{$key}' in class " . static::class);
    }

    public static function toArray(): array
    {
        $calledClass = get_called_class();

        if (!array_key_exists($calledClass, self::$constCache)) {
            $reflect                        = new \ReflectionClass(get_called_class());
            self::$constCache[$calledClass] = $reflect->getConstants();
        }

        return self::$constCache[$calledClass];
    }

    public static function keys(): array
    {
        return array_keys(self::toArray());
    }

    public static function values(): array
    {
        return array_values(self::toArray());
    }

    public static function isValidKey(string $key, bool $strict = true): bool
    {
        $keys = self::keys();

        if ($strict) {
            return in_array($key, $keys);
        }

        $keys = array_map('strtolower', $keys);

        return in_array(strtolower($key), $keys);
    }

    public static function isValidValue(int | string $value, bool $strict = true): bool
    {
        return in_array($value, self::values(), $strict);
    }

    public function value(): string
    {
        return (string) $this->value;
    }

    public static function getKey(int | string $value): string | false
    {
        return array_search($value, static::toArray(), true);
    }

    final public function equals(Enum $enum): bool
    {
        return static::class === get_class($enum) && $this->value === $enum->value();
    }

    public function translate(): string
    {
        return trans($this->getTranslationPrefix() . $this->value);
    }

    public static function except(array | string $values): array
    {
        return array_diff(static::values(), (array) $values);
    }

    public static function getSelectArray(array $onlyKeys = []): array
    {
        $keys = self::keys();

        if (!empty($onlyKeys)) {
            $keys = array_intersect($keys, array_map('strtoupper', $onlyKeys));
        }

        return static::translateKeys($keys);
    }

    protected static function translateKeys(array $keys): array
    {
        $translated = [];
        $array      = self::toArray();

        foreach ($keys as $key) {
            $translated[$array[$key]] = trans(static::getTranslationPrefix() . $array[$key]);
        }

        return $translated;
    }

    protected static function getTranslationPrefix(): string
    {
        return '';
    }
}
