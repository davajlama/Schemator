<?php

declare(strict_types=1);

namespace Davajlama\Schemator\DataSanitizer;

use Davajlama\Schemator\DataSanitizer\Filters\FiltersFactory;
use Davajlama\Schemator\Schema\Extractor\ArrayExtractor;
use Davajlama\Schemator\Schema\Extractor\ExtractorAwareInterface;

use function is_array;

class ArrayDataSanitizer
{
    private ArrayExtractor $arrayExtractor;

    private FiltersFactory $filtersFactory;

    /**
     * @var PropertiesGroup[]
     */
    private array $propertiesGroups = [];

    /**
     * @var ArrayReference[]
     */
    private array $arrayReferences = [];

    public function __construct(?ArrayExtractor $arrayExtractor = null, ?FiltersFactory $filtersFactory = null)
    {
        $this->arrayExtractor = $arrayExtractor ?? new ArrayExtractor();
        $this->filtersFactory = $filtersFactory ?? new FiltersFactory();
    }

    public function ref(string $property, ?self $arrayDataSanitizer = null): self
    {
        $reference = null;
        foreach ($this->arrayReferences as $existedReference) {
            if ($existedReference->getName() === $property) {
                $reference = $existedReference;
                break;
            }
        }

        if ($reference === null) {
            $reference = new ArrayReference($property, $arrayDataSanitizer ?? new ArrayDataSanitizer($this->arrayExtractor));
        }

        $this->arrayReferences[$property] = $reference;

        return $reference->getArrayDataSanitizer();
    }

    public function props(string ...$properties): PropertiesGroup
    {
        return $this->propertiesGroups[] = new PropertiesGroup($properties, $this->filtersFactory);
    }

    /**
     * @param mixed[] $payload
     * @return mixed[]
     */
    public function sanitize(array $payload): array
    {
        foreach ($this->propertiesGroups as $propertiesGroup) {
            foreach ($propertiesGroup->getProperties() as $property) {
                foreach ($propertiesGroup->getFilters() as $filter) {
                    if ($filter instanceof ExtractorAwareInterface) {
                        $filter->setExtractor($this->arrayExtractor);
                    }

                    $sanitizedValue = $filter->filter($payload, $property);
                    if ($sanitizedValue !== null) {
                        $payload[$property] = $sanitizedValue->getValue();
                    }
                }
            }
        }

        foreach ($this->arrayReferences as $arrayReference) {
            if ($this->arrayExtractor->exists($payload, $arrayReference->getName())) {
                $subPayload = $this->arrayExtractor->extract($payload, $arrayReference->getName());
                if (is_array($subPayload)) {
                    $payload[$arrayReference->getName()] = $arrayReference->getArrayDataSanitizer()->sanitize($subPayload);
                }
            }
        }

        return $payload;
    }
}
