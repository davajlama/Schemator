<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Validator;

use function array_unshift;
use function count;
use function implode;

final class MessageFormatter
{
    /**
     * @param ErrorMessage[] $errors
     * @return string[]
     */
    public static function formatErrors(array $errors): array
    {
        $list = [];
        foreach ($errors as $error) {
            $path = $error->getPath();
            array_unshift($path, '^');

            if (count($error->getErrors()) > 0) {
                foreach ($error->getErrors() as $e2) {
                    $path = $error->getPath();
                    array_unshift($path, '^');
                    $path[] = $error->getProperty() . '[' . $e2->getIndex() . ']';

                    $path = implode('->', $path);

                    $list[] = '[' . $path . '] ' . $e2->getProperty() . ' : ' . $e2->getMessage();
                }
            } else {
                $path = $error->getPath();
                array_unshift($path, '^');

                $path = implode('->', $path);

                $list[] = '[' . $path . '] ' . $error->getProperty() . ' : ' . $error->getMessage();
            }
        }

        return $list;
    }
}
