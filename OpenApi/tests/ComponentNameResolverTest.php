<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Tests;

use Davajlama\Schemator\Demo\BookStore\Schema\Contact;
use Davajlama\Schemator\OpenApi\Api;
use Davajlama\Schemator\OpenApi\ComponentNameResolverInterface;
use Davajlama\Schemator\OpenApi\DefaultComponentNameResolver;
use Davajlama\Schemator\OpenApi\OpenApiBuilder;
use Davajlama\Schemator\Schema\Schema;
use PHPUnit\Framework\TestCase;

use function strrchr;
use function substr;

final class ComponentNameResolverTest extends TestCase
{
    public function testCustomResolverOverridesComponentName(): void
    {
        $api = new Api();
        $api->post('/contacts')->jsonRequestBody(Contact::class);

        $builder = new OpenApiBuilder();
        $builder->prependComponentNameResolver(new class implements ComponentNameResolverInterface {
            public function support(Schema|string $schema): bool
            {
                return true;
            }

            public function resolve(Schema|string $schema): string
            {
                $name = $schema instanceof Schema ? $schema::class : $schema;

                return substr((string) strrchr('\\' . $name, '\\'), 1);
            }
        });

        $spec = $builder->build($api);

        $schemas = self::extract($spec, 'components', 'schemas');
        self::assertIsArray($schemas);
        self::assertArrayHasKey('Contact', $schemas);
        self::assertArrayNotHasKey('DavajlamaSchematorDemoBookStoreSchemaContact', $schemas);

        $ref = self::extract($spec, 'paths', '/contacts', 'post', 'requestBody', 'content', 'application/json', 'schema', '$ref');
        self::assertSame('#/components/schemas/Contact', $ref);
    }

    public function testUnsupportedSchemaFallsBackToDefaultName(): void
    {
        $api = new Api();
        $api->post('/contacts')->jsonRequestBody(Contact::class);

        $builder = new OpenApiBuilder();
        $builder->prependComponentNameResolver(new class implements ComponentNameResolverInterface {
            public function support(Schema|string $schema): bool
            {
                return false;
            }

            public function resolve(Schema|string $schema): string
            {
                return 'Unused';
            }
        });

        $spec = $builder->build($api);

        $schemas = self::extract($spec, 'components', 'schemas');
        self::assertIsArray($schemas);
        self::assertArrayHasKey('DavajlamaSchematorDemoBookStoreSchemaContact', $schemas);
    }

    public function testDefaultResolverCanBeExtended(): void
    {
        $api = new Api();
        $api->post('/contacts')->jsonRequestBody(Contact::class);

        $builder = new OpenApiBuilder();
        $builder->prependComponentNameResolver(new class extends DefaultComponentNameResolver {
            public function resolve(Schema|string $schema): string
            {
                $name = $schema instanceof Schema ? $schema::class : $schema;

                return $this->capitalize((string) strrchr('\\' . $name, '\\'));
            }
        });

        $spec = $builder->build($api);

        $schemas = self::extract($spec, 'components', 'schemas');
        self::assertIsArray($schemas);
        self::assertArrayHasKey('Contact', $schemas);
    }

    public function testLastRegisteredResolverHasHighestPriority(): void
    {
        $api = new Api();
        $api->post('/contacts')->jsonRequestBody(Contact::class);

        $builder = new OpenApiBuilder();
        $builder->prependComponentNameResolver($this->createNamedResolver('First'));
        $builder->prependComponentNameResolver($this->createNamedResolver('Second'));

        $spec = $builder->build($api);

        $schemas = self::extract($spec, 'components', 'schemas');
        self::assertIsArray($schemas);
        self::assertArrayHasKey('Second', $schemas);
        self::assertArrayNotHasKey('First', $schemas);
    }

    private function createNamedResolver(string $name): ComponentNameResolverInterface
    {
        return new class ($name) implements ComponentNameResolverInterface {
            public function __construct(private string $name)
            {
            }

            public function support(Schema|string $schema): bool
            {
                return true;
            }

            public function resolve(Schema|string $schema): string
            {
                return $this->name;
            }
        };
    }

    private static function extract(mixed $data, string ...$path): mixed
    {
        foreach ($path as $key) {
            self::assertIsArray($data);
            self::assertArrayHasKey($key, $data);
            $data = $data[$key];
        }

        return $data;
    }
}
