<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\OpenApi\PropertyHelper;

class RequestBody
{
    use PropertyHelper;

    /**
     * @var Content[]|null
     */
    private ?array $contents = null;

    public function build(): array
    {
        return [
            $this->prop('content', $this->buildContents()),
        ];
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
            throw new \LogicException(sprintf('Content with type %s already exists.', $content->getType()));
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