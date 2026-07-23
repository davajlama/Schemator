<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Tests;

use Davajlama\Schemator\OpenApi\Api;
use PHPUnit\Framework\TestCase;

final class InfoTest extends TestCase
{
    public function testContactIsBuiltUnderInfo(): void
    {
        $api = new Api();
        $api->info()
            ->title('Zaslat API v1')
            ->version('1.0.0')
            ->contact()
                ->name('Zaslat API Support')
                ->url('https://docs.zaslat.cz/api/v1/content')
                ->email('support@zaslat.cz');

        $spec = $api->build();

        self::assertSame([
            'version' => '1.0.0',
            'title' => 'Zaslat API v1',
            'contact' => [
                'name' => 'Zaslat API Support',
                'url' => 'https://docs.zaslat.cz/api/v1/content',
                'email' => 'support@zaslat.cz',
            ],
        ], $spec['info']);
    }

    public function testContactIsReused(): void
    {
        $api = new Api();

        self::assertSame($api->info()->contact(), $api->info()->contact());
    }

    public function testLicenseAndTermsOfServiceAreBuiltUnderInfo(): void
    {
        $api = new Api();
        $api->info()->termsOfService('https://example.com/terms');
        $api->info()->license('MIT')->url('https://opensource.org/licenses/MIT');

        $spec = $api->build();

        self::assertSame([
            'termsOfService' => 'https://example.com/terms',
            'license' => [
                'name' => 'MIT',
                'url' => 'https://opensource.org/licenses/MIT',
            ],
        ], $spec['info']);
    }
}
