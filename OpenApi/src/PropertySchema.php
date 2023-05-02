<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\Schema\Rules\RulesFactory;

final class PropertySchema
{
    public static function prop(): Property
    {
        return new Property(new RulesFactory());
    }

    /**
     * @param scalar[] $values
     */
    public static function enum(array $values): Property
    {
        return self::prop()->enum($values);
    }
}
