<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Validator;

use function count;

final class MessageFormatter
{
    /**
     * @param ErrorMessage[] $errors
     * @return array<array{field: string, message: string}>
     */
    public static function toFlatten(array $errors): array
    {
        return self::format($errors);
    }

    /**
     * @param ErrorMessage[] $errors
     * @return array<array{field: string, message: string}>
     */
    private static function format(array $errors, ?string $path = null): array
    {
        $list = [];
        foreach ($errors as $error) {
            $index = $error->getIndex() !== null ? '[' . $error->getIndex() . ']' : '';
            $prefix = $path !== null ? $path . $index . '.' : '';
            $field = $prefix . $error->getProperty();
            if (count($error->getErrors()) === 0) {
                $list[] = [
                    'field' => $field,
                    'message' => $error->getMessage(),
                ];
            } else {
                foreach (self::format($error->getErrors(), $field) as $subError) {
                    $list[] = $subError;
                }
            }
        }

        return $list;
    }
}
