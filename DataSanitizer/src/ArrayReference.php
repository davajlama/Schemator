<?php

declare(strict_types=1);

namespace Davajlama\Schemator\DataSanitizer;

final class ArrayReference
{
    private string $name;

    private ArrayDataSanitizer $arrayDataSanitizer;

    public function __construct(string $name, ArrayDataSanitizer $arrayDataSanitizer)
    {
        $this->name = $name;
        $this->arrayDataSanitizer = $arrayDataSanitizer;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getArrayDataSanitizer(): ArrayDataSanitizer
    {
        return $this->arrayDataSanitizer;
    }
}
