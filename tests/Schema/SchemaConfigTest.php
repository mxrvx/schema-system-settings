<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\Tests;

use MODX\Revolution\modX;
use MXRVX\Schema\System\Settings\SchemaConfig;
use MXRVX\Schema\System\Settings\Setting;
use HJerichen\DBUnit\Dataset\Dataset;
use HJerichen\DBUnit\Dataset\DatasetArray;
use HJerichen\DBUnit\MySQLTestCaseTrait;
use Symfony\Component\Filesystem\Filesystem;

/** @covers \MXRVX\Schema\System\Settings\SchemaConfig */
class SchemaConfigTest extends BaseTest
{
    use MySQLTestCaseTrait {
        setUpDatabase as public setUpDb;
        tearDown as public tearDownDb;
        assertDatasetEqualsCurrent as private assertDatasetEqualsCurrentDataset;
    }

    protected \PDO $database;
    protected Modx $modx;
    protected SchemaConfig $schemaConfig;

    public function testImportAndCompare(): void
    {
        $expected = new DatasetArray($this->getDatasetSettings());

        $this->assertEquals(1, 1);
        $this->assertDatasetEqualsCurrent($expected);
    }

    public function testGetSettingValueFromSchemaConfig(): void
    {
        $schemaConfig = $this->schemaConfig;
        foreach ($schemaConfig->getSettings() as $setting) {
            $key = $setting->getKey();

            $expected = $setting->getValue();
            $actual = $schemaConfig->getSettingValue($key);

            $this->assertNotNull($expected);
            $this->assertEquals($expected, $actual, $schemaConfig->getSettingKey($key));
        }
    }

    public function testSetSettingValueFromSchemaConfig(): void
    {
        $schemaConfig = $this->schemaConfig;

        $settings = [];
        foreach ($schemaConfig->getSettings() as $setting) {

            $key = $setting->getKey();
            $expected = \mt_rand(1, 100);
            $schemaConfig->setSettingValue($key, $expected);

            $actual = $schemaConfig->getSettingValue($key);

            $this->assertNotNull($expected);
            $this->assertEquals($expected, $actual, $schemaConfig->getSettingKey($key));

            $settingKey = $schemaConfig->getSettingKey($key);
            $settings[$settingKey] = $actual;

            $settingKey = $schemaConfig->getSettingKey($key);
            $actual = $this->modx->getOption($settingKey, null);

            $this->assertEquals($expected, $actual, $schemaConfig->getSettingKey($key));
        }
    }

    public function testSetSettingValueFromModxSetOption(): void
    {
        $schemaConfig = $this->schemaConfig;

        foreach ($schemaConfig->getSettings() as $setting) {

            $key = $setting->getKey();
            $expected = \mt_rand(1, 100);

            $settingKey = $schemaConfig->getSettingKey($key);
            $this->modx->setOption($settingKey, $expected);

            $actual = $schemaConfig->getSettingValue($key);

            $this->assertNotNull($expected);
            $this->assertEquals($expected, $actual, $schemaConfig->getSettingKey($key));
        }
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDb();

        if (\is_dir(MODX_CORE_PATH . 'cache/')) {
            if ($filesystem = new Filesystem()) {
                $filesystem->remove(MODX_CORE_PATH . 'cache/');
                $filesystem->mkdir(MODX_CORE_PATH . 'cache/');
            }
        }

        if ($modx = Modx::getInstance(\md5(self::class . \time()))) {
            $modx->initialize();
            $modx->switchContext('web', true);
            $this->modx = $modx;
        }

        foreach ($this->getTestingSettings() as $key => $value) {
            $this->schema->withSetting(
                Setting::define(
                    key: $key,
                    value: 'default',
                    xtype: 'textfield',
                ),
            );
        }

        $this->schemaConfig = SchemaConfig::define($this->schema)->withConfig($this->modx->config);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->tearDownDb();
        unset($this->modx);
        unset($this->schemaConfig);
    }

    protected function getDatabase(): \PDO
    {
        if (!isset($this->database)) {
            $this->database = new \PDO(\getenv('DB_DSN'), \getenv('DB_USERNAME'), \getenv('DB_PASSWORD'));
            $this->database->exec("SET SESSION sql_mode='STRICT_TRANS_TABLES'");
            $this->createTables();
        }

        return $this->database;
    }

    protected function getDatasetSettings(): array
    {
        $dataset = [];
        foreach ($this->getTestingSettings(true) as $key => $value) {
            $dataset[] = ['key' => $key, 'value' => $value];
        }

        return ['system_settings' => $dataset];
    }

    protected function getDatasetForSetup(): Dataset
    {
        return $this->getDatasetForSetupFromAttribute() ?? new DatasetArray(
            [
                'context' => [
                    ['key' => 'mgr', 'name' => 'Manager'],
                    ['key' => 'web', 'name' => 'Web'],
                    ['key' => 'web2', 'name' => 'Web2'],
                ],
            ] + $this->getDatasetSettings(),
        );
    }

    private function createTables(): void
    {
        $sql = \file_get_contents(\getenv('DB_TEST_SQL_FILE'));
        \assert($sql !== false);

        $this->database->exec($sql);
    }
}
