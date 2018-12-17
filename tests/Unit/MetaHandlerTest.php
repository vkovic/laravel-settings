<?php

namespace Vkovic\LaravelSettings\Test\Unit;

use Vkovic\LaravelSettings\MetaHandler;
use Vkovic\LaravelSettings\Test\TestCase;

class MetaHandlerTest extends TestCase
{
    /**
     * Valid data provider for: key, value and type
     *
     * @return array
     */
    public function keyValueTypeProvider()
    {
        return [
            // key | value
            [str_random(), str_random()],
            [str_random(), null],
            [str_random(), 1],
            [str_random(), 1.1],
            [str_random(), true],
            [str_random(), false],
            [str_random(), []],
            [str_random(), range(1, 10)],
        ];
    }

    /**
     * @test
     * @dataProvider keyValueTypeProvider
     */
    public function it_can_set_and_get_meta($key, $value)
    {
        $metaHandler = new MetaHandler();

        $metaHandler->set($key, $value);

        $this->assertSame($metaHandler->get($key), $value);
    }

    /**
     * @test
     * @dataProvider keyValueTypeProvider
     */
    public function it_can_create_meta($key, $value)
    {
        $metaHandler = new MetaHandler();

        $metaHandler->set($key, $value);
        $metaHandler->update($key, $value);

        $this->assertSame($metaHandler->get($key), $value);
    }

    /**
     * @test
     * @dataProvider keyValueTypeProvider
     */
    public function it_can_update_meta($key, $value)
    {
        $metaHandler = new MetaHandler();

        $metaHandler->set($key, $value);
        $metaHandler->update($key, $value);

        $this->assertSame($metaHandler->get($key), $value);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_updating_non_existing_meta()
    {
        $this->expectExceptionMessage("Can't update");

        $metaHandler = new MetaHandler();

        $metaHandler->update('unexistingKey', '');
    }

    /**
     * @test
     */
    public function it_throws_exception_when_creating_same_meta()
    {
        $this->expectExceptionMessage("Can't create");

        $metaHandler = new MetaHandler();

        $metaHandler->set('foo', 'bar');

        $metaHandler->create('foo', '');
    }

    /**
     * @test
     */
    public function it_will_return_default_value_when_key_not_exist()
    {
        $metaHandler = new MetaHandler();

        $default = str_random();

        $this->assertEquals($default, $metaHandler->get('nonExistingKey', $default));
    }

    /**
     * @test
     * @dataProvider keyValueTypeProvider
     */
    public function it_can_check_meta_exists($key, $value)
    {
        $metaHandler = new MetaHandler();

        $metaHandler->set($key, $value);

        $this->assertTrue($metaHandler->exists($key));
        $this->assertFalse($metaHandler->exists(str_random()));
    }

    /**
     * @test
     */
    public function it_can_count_meta()
    {
        //
        // Check zero count
        //

        $metaHandler = new MetaHandler();

        $this->assertTrue($metaHandler->count() === 0);

        //
        // Check count in default realm
        //

        $count = rand(0, 10);
        for ($i = 0; $i < $count; $i++) {
            $key = str_random();
            $value = str_random();
            $metaHandler->set($key, $value);
        }

        $this->assertTrue($metaHandler->count() === $count);
    }

    /**
     * @test
     */
    public function it_can_get_all_meta()
    {
        $metaHandler = new MetaHandler();

        $key1 = str_random();
        $value1 = str_random();
        $metaHandler->set($key1, $value1);

        $key2 = str_random();
        $value2 = str_random();
        $metaHandler->set($key2, $value2);

        $this->assertEquals([
            $key1 => $value1,
            $key2 => $value2,
        ], $metaHandler->all());
    }


    /**
     * @test
     */
    public function it_can_get_all_keys()
    {
        $metaHandler = new MetaHandler();

        $count = rand(0, 10);

        if ($count === 0) {
            $this->assertEmpty($metaHandler->keys());
        }

        $keysToSave = [];
        for ($i = 0; $i < $count; $i++) {
            $key = str_random();
            $keysToSave[] = $key;

            $metaHandler->set($key, '');
        }

        $metaKeys = $metaHandler->keys();

        foreach ($keysToSave as $keyToSave) {
            $this->assertContains($keyToSave, $metaKeys);
        }
    }

    /**
     * @test
     */
    public function it_can_remove_meta_by_key()
    {
        $metaHandler = new MetaHandler();

        $key = str_random();
        $value = str_random();

        $metaHandler->set($key, $value);
        $metaHandler->remove($key);

        $this->assertEmpty($metaHandler->all());
    }

    /**
     * @test
     */
    public function it_can_purge_meta()
    {
        $metaHandler = new MetaHandler();

        $count = rand(0, 10);
        for ($i = 0; $i < $count; $i++) {
            $key = str_random();
            $value = str_random();
            $metaHandler->set($key, $value);
        }

        $metaHandler->purge();

        $this->assertEmpty($metaHandler->all());
    }
}
