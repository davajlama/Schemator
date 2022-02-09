<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

use function array_unshift;
use function implode;

final class MessagesFormatter
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

            if ($error->getErrors()) {
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
