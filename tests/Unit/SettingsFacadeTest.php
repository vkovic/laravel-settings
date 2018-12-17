<?php

namespace Vkovic\LaravelSettings\Test\Unit;

use Settings;
use Vkovic\LaravelSettings\Models\Settings as SettingsModel;
use Vkovic\LaravelSettings\Test\TestCase;

class SettingsFacadeTest extends TestCase
{
    /**
     * Valid data provider for: key and value
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
     */
    public function it_saves_to_correct_realm()
    {
        Settings::set('foo', '');

        $this->assertDatabaseHas('meta', [
            'realm' => SettingsModel::getRealm()
        ]);
    }

    /**
     * @test
     * @dataProvider keyValueTypeProvider
     */
    public function it_can_set_and_get_settings($key, $value)
    {
        Settings::set($key, $value);

        $this->assertSame(Settings::get($key), $value);
    }

    /**
     * @test
     * @dataProvider keyValueTypeProvider
     */
    public function it_can_create_settings($key, $value)
    {
        Settings::create($key, $value);

        $this->assertSame(Settings::get($key), $value);
    }

    /**
     * @test
     * @dataProvider keyValueTypeProvider
     */
    public function it_can_update_settings($key, $value)
    {
        $newValue = str_random();

        Settings::set($key, $value);
        Settings::update($key, $newValue);

        $this->assertSame(Settings::get($key), $newValue);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_updating_non_existing_settings()
    {
        $this->expectExceptionMessage("Can't update");

        Settings::update('unexistingKey', '');
    }

    /**
     * @test
     */
    public function it_throws_exception_when_creating_same_settings()
    {
        $this->expectExceptionMessage("Can't create");

        Settings::set('foo', 'bar');

        Settings::create('foo', '');
    }

    /**
     * @test
     */
    public function it_will_return_default_value_when_key_not_exist()
    {
        $default = str_random();

        $this->assertEquals($default, Settings::get('nonExistingKey', $default));
    }

    /**
     * @test
     * @dataProvider keyValueTypeProvider
     */
    public function it_can_check_settings_exists($key, $value)
    {
        Settings::set($key, $value);

        $this->assertTrue(Settings::exists($key));
        $this->assertFalse(Settings::exists(str_random()));
    }

    /**
     * @test
     */
    public function it_can_count_settings()
    {
        // Check zero count
        $this->assertTrue(Settings::count() === 0);

        //
        // Check count in default realm
        //

        $count = rand(0, 10);
        for ($i = 0; $i < $count; $i++) {
            $key = str_random();
            $value = str_random();
            Settings::set($key, $value);
        }

        $this->assertTrue(Settings::count() === $count);
    }

    /**
     * @test
     */
    public function it_can_get_all_settings()
    {
        $key1 = str_random();
        $value1 = str_random();
        Settings::set($key1, $value1);

        $key2 = str_random();
        $value2 = range(0, 10);
        Settings::set($key2, $value2);

        $this->assertEquals([
            $key1 => $value1,
            $key2 => $value2,
        ], Settings::all());
    }

    /**
     * @test
     */
    public function it_can_get_all_keys()
    {
        $this->assertEmpty(Settings::keys());

        $keysToSave = [];
        for ($i = 0; $i < rand(1, 10); $i++) {
            $key = str_random();
            $keysToSave[] = $key;

            Settings::set($key, '');
        }

        $settingsKeys = Settings::keys();

        foreach ($keysToSave as $keyToSave) {
            $this->assertContains($keyToSave, $settingsKeys);
        }
    }

    /**
     * @test
     */
    public function it_can_remove_settings_by_key()
    {
        $key = str_random();
        $value = str_random();

        Settings::set($key, $value);
        Settings::remove($key);

        $this->assertEquals(0, Settings::count());
    }

    /**
     * @test
     */
    public function it_can_purge_settings()
    {
        $count = rand(0, 10);
        for ($i = 0; $i < $count; $i++) {
            $key = str_random();
            $value = str_random();
            Settings::set($key, $value);
        }

        Settings::purge();

        $this->assertEquals(0, Settings::count());
    }
}
