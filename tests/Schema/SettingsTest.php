<?php

declare(strict_types=1);

namespace MXRVX\Schema\System\Settings\Tests;

use MXRVX\Schema\System\Settings\Setting;

/** @covers \MXRVX\Schema\System\Settings\Schema */
class SettingsTest extends BaseTest
{
    public function testCount(): void
    {
        $this->settings->set('key1', Setting::define('key1', '', '', ''));
        $this->settings->set('key2', Setting::define('key2', '', '', ''));

        $this->assertSame(2, $this->settings->count());
    }

    public function testHas(): void
    {
        $this->settings->set('key1', Setting::define('key1', '', '', ''));

        $this->assertTrue($this->settings->has('key1'));
        $this->assertFalse($this->settings->has('key_not_exist'));
    }

    public function testSetGet(): void
    {
        $this->assertFalse($this->settings->has('id'));

        $this->settings->set('id', $s = Setting::define('id', '', '', ''));

        $this->assertTrue($this->settings->has('id'));
        $this->assertSame($s, $this->settings->get('id'));

        $this->assertSame(['id' => $s], \iterator_to_array($this->settings->getIterator()));
    }

    public function testRemove(): void
    {
        $this->assertFalse($this->settings->has('key_not_exist'));

        $this->settings->set('id', $s = Setting::define('id', '', '', ''));
        $this->assertTrue($this->settings->has($s->getKey()));

        $this->settings->remove($s->getKey());
        $this->assertFalse($this->settings->has($s->getKey()));
    }

    public function testGetKeys(): void
    {
        foreach (['id','id2','id3'] as $key) {
            $this->settings->set($key, Setting::define($key, '', '', ''));
        }

        $keys = [];
        foreach (\iterator_to_array($this->settings->getIterator()) as $key => $setting) {
            $keys[] = $key;
        }

        $this->assertSame($keys, $this->settings->getKeys());
    }
}
