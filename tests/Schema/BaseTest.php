<?php


declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\Tests;

use MXRVX\Schema\System\Settings\Schema;
use MXRVX\Schema\System\Settings\Settings;
use MXRVX\Schema\System\Settings\TypeCasters\StringCaster;
use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    /** @var non-empty-string */
    protected string $namespace = 'test-app';

    protected Schema $schema;
    protected Settings $settings;

    public function getTestingSettings(bool $withNamespace = false): array
    {
        $settings = [];
        foreach (
            [
                'key1' => 'value1',
                'key2' => 'value2',
                'key3' => [
                    'value1',
                    'value2',
                ],
                'key4' => false,
                'key5' => true,
                'key6' => '1,2,3,4',
                'key7' => 1234567890,
                'key8' => 0.0111,
                'key9' => \json_encode([1, 2, 3]),
            ] as $k => $v
        ) {
            if ($withNamespace) {
                $k = $this->namespace . '.' . $k;
            }
            $settings[$k] = StringCaster::cast($v);
        }

        return $settings;
    }

    /**
     * Init all we need.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->schema = Schema::define($this->namespace);
        $this->settings = $this->schema->getSettings();
    }

    /**
     * Cleanup.
     */
    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->namespace);
        unset($this->schema);
        unset($this->settings);
    }
}
