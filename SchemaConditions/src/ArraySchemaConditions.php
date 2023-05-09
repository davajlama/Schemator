<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Extractor\ArrayExtractor;
use Davajlama\Schemator\Schema\Extractor\ExtractorAwareInterface;
use Davajlama\Schemator\SchemaConditions\Conditions\ConditionsFactory;

use function count;

final class ArraySchemaConditions
{
    private ConditionsFactory $conditionsFactory;

    private ArrayExtractor $arrayExtractor;

    /**
     * @var PropertiesGroup[]
     */
    private array $propertiesGroups;

    public function __construct(?ConditionsFactory $conditionsFactory = null, ?ArrayExtractor $arrayExtractor = null)
    {
        $this->conditionsFactory = $conditionsFactory ?? new ConditionsFactory();
        $this->arrayExtractor = $arrayExtractor ?? new ArrayExtractor();
    }

    public function props(string ...$properties): PropertiesGroup
    {
        return $this->propertiesGroups[] = new PropertiesGroup($properties, $this->conditionsFactory);
    }

    /**
     * @param mixed[] $payload
     */
    public function validate(array $payload): void
    {
        $errors = [];
        foreach ($this->propertiesGroups as $propertiesGroup) {
            foreach ($propertiesGroup->getConditions() as $condition) {
                if ($condition instanceof ExtractorAwareInterface) {
                    $condition->setExtractor($this->arrayExtractor);
                }

                try {
                    $condition->validate($payload);
                } catch (ValidationFailedException $e) {
                    foreach ($e->getErrors() as $error) {
                        $errors[] = $error;
                    }
                }
            }
        }

        if (count($errors) > 0) {
            throw new ValidationFailedException('Data is not valid.', $errors);
        }
    }
}
