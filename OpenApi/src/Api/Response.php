<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\OpenApi\DefinitionInterface;
use Davajlama\Schemator\OpenApi\PropertyHelper;
use Davajlama\Schemator\Schema\Schema;
use LogicException;

use function is_array;
use function sprintf;

class Response implements DefinitionInterface
{
    use PropertyHelper;

    private int $status;

    private ?string $description = null;

    /**
     * @var Content[]|null
     */
    private ?array $contents = null;

    public function __construct(int $status)
    {
        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return mixed[]
     */
    public function build(): array
    {
        return [
            $this->status => $this->join(
                $this->prop('description', $this->description),
                $this->prop('content', $this->buildContents()),
            ),
        ];
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function json(Schema|string|null $schema = null): self
    {
        $content = $this->jsonContent();
        if ($schema !== null) {
            $content->schema($schema);
        }

        return $this;
    }

    public function jsonContent(): Content
    {
        return $this->content('application/json');
    }

    /**
     * @param mixed[]|string $json
     */
    public function addJsonExample(array|string $json, string|null $summary): self
    {
        $example = is_array($json) ? new Example($json, $summary) : new JsonExample($json, $summary);

        $this->jsonContent()->addExample($example);

        return $this;
    }

    public function addJsonFileExample(string $jsonFile, string|null $summary): self
    {
        $this->jsonContent()->addExample(new JsonFileExample($jsonFile, $summary));

        return $this;
    }

    public function content(string $type): Content
    {
        $content = $this->findContent($type);
        if ($content === null) {
            $content = new Content($type);
            $this->addContent($content);
        }

        return $content;
    }

    public function addContent(Content $content): self
    {
        if ($this->contents === null) {
            $this->contents = [];
        }

        if ($this->findContent($content->getType()) !== null) {
            throw new LogicException(sprintf('Content with type %s already exists.', $content->getType()));
        }

        $this->contents[] = $content;

        return $this;
    }

    protected function findContent(string $type): ?Content
    {
        if ($this->contents !== null) {
            foreach ($this->contents as $content) {
                if ($content->getType() === $type) {
                    return $content;
                }
            }
        }

        return null;
    }

    /**
     * @return mixed[]|null
     */
    protected function buildContents(): ?array
    {
        $result = null;
        if ($this->contents !== null) {
            $result = [];
            foreach ($this->contents as $content) {
                $result = $this->join($result, $content->build());
            }
        }

        return $result;
    }
}
