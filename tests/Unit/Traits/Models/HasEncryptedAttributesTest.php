<?php

namespace Tests\Traits\Models;

use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Artisan;
use Tests\Fixtures\Models\TestModel;
use Carbon\Carbon;

class HasEncryptedAttributesTest extends TestCase
{
    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/../../../Fixtures/Migrations');
        $this->withFactories(__DIR__.'/../../../Fixtures/Factories');
        Artisan::call('db:seed', ['--class' => 'Tests\Fixtures\Seeds\TestDatabaseSeeder']);
    }


    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * @return void
     */
    public function testBulkSeedWithoutNulls_allTypesCorrectViaGetProperty_Test()
    {
        $models = TestModel::where('name', '!=', 'testNulls')->where('name', '!=', 'testNullsEmptyStrings')->get();

        $this->assertEquals(503, count($models));

        foreach ($models as $model) {

            $this->assertInternalType('string', $model->name);
            $this->assertInternalType('string', $model->encrypt_string);
            $this->assertInternalType('integer', $model->encrypt_integer);
            $this->assertInternalType('boolean', $model->encrypt_boolean);
            $this->assertInternalType('boolean', $model->encrypt_another_boolean);
            $this->assertInternalType('float', $model->encrypt_float);
            $this->assertInstanceOf(Carbon::class, $model->encrypt_date);
            $this->assertInternalType('string', $model->encrypt_string_bi);
            $this->assertInternalType('string', $model->encrypt_integer_bi);
            $this->assertInternalType('string', $model->encrypt_boolean_bi);
            $this->assertInternalType('string', $model->encrypt_float_bi);
            $this->assertInternalType('string', $model->encrypt_date_bi);
            $this->assertInternalType('string', $model->hash_string);
        }

    }

    /**
     * @return void
     */
    public function testBulkSeedWithNulls_allTypesCorrectViaGetProperty_Test()
    {
        $models = TestModel::where('name', '=', 'testNulls')->get();

        $this->assertEquals(1, count($models));

        foreach ($models as $model) {
            $this->assertTrue(is_null($model->encrypt_string));
            $this->assertTrue(is_null($model->encrypt_integer));
            $this->assertTrue(is_null($model->encrypt_boolean));
            $this->assertTrue(is_null($model->encrypt_another_boolean));
            $this->assertTrue(is_null($model->encrypt_date));
            $this->assertTrue(is_null($model->encrypt_string_bi));
            $this->assertTrue(is_null($model->encrypt_integer_bi));
            $this->assertTrue(is_null($model->encrypt_boolean_bi));
            $this->assertTrue(is_null($model->encrypt_float_bi));
            $this->assertTrue(is_null($model->encrypt_date_bi));
            $this->assertTrue(is_null($model->hash_string));
            $this->assertTrue(is_null($model->encrypt_float));
        }
    }

    /**
     * @return void
     */
    public function testBulkSeedWithEmptyStrings_allTypesCorrectViaGetProperty_Test()
    {
        $models = TestModel::where('name', '=', 'testNullsEmptyStrings')->get();

        $this->assertEquals(1, count($models));

        foreach ($models as $model) {
            $this->assertEquals('', $model->encrypt_string);
            $this->assertInternalType('string', $model->encrypt_string);
            $this->assertTrue(is_null($model->encrypt_integer));
            $this->assertTrue(is_null($model->encrypt_boolean));
            $this->assertTrue(is_null($model->encrypt_another_boolean));
            $this->assertTrue(is_null($model->encrypt_date));
            $this->assertInternalType('string', $model->encrypt_string_bi);
            $this->assertTrue(is_null($model->encrypt_integer_bi));
            $this->assertTrue(is_null($model->encrypt_boolean_bi));
            $this->assertTrue(is_null($model->encrypt_float_bi));
            $this->assertTrue(is_null($model->encrypt_date_bi));
            $this->assertEquals('', $model->hash_string);
            $this->assertInternalType('string', $model->hash_string);
            $this->assertTrue(is_null($model->encrypt_float));
        }
    }


    /**
     * @return void
     */
    public function testBulkSeedWithoutNulls_allTypesCorrectViaToArray_Test()
    {
        $models = TestModel::where('name', '!=', 'testNulls')->where('name', '!=', 'testNullsEmptyStrings')->get();

        $this->assertEquals(503, count($models));

        foreach ($models as $model) {

            $array = $model->toArray();

            $this->assertInternalType('string', $array['name']);
            $this->assertInternalType('string', $array['encrypt_string']);
            $this->assertInternalType('integer', $array['encrypt_integer']);
            $this->assertInternalType('boolean', $array['encrypt_boolean']);
            $this->assertInternalType('boolean', $array['encrypt_another_boolean']);
            $this->assertInternalType('float', $array['encrypt_float']);
            $this->assertInstanceOf(Carbon::class, $array['encrypt_date']);
            $this->assertInternalType('string', $array['encrypt_string_bi']);
            $this->assertInternalType('string', $array['encrypt_integer_bi']);
            $this->assertInternalType('string', $array['encrypt_boolean_bi']);
            $this->assertInternalType('string', $array['encrypt_float_bi']);
            $this->assertInternalType('string', $array['encrypt_date_bi']);
            $this->assertInternalType('string', $array['hash_string']);
        }

    }

    /**
     * @return void
     */
    public function testBulkSeedWithNulls_allTypesCorrectViaToArray_Test()
    {
        $models = TestModel::where('name', '=', 'testNulls')->get();

        $this->assertEquals(1, count($models));

        foreach ($models as $model) {

            $array = $model->toArray();

            $this->assertTrue(is_null($array['encrypt_string']));
            $this->assertTrue(is_null($array['encrypt_integer']));
            $this->assertTrue(is_null($array['encrypt_boolean']));
            $this->assertTrue(is_null($array['encrypt_another_boolean']));
            $this->assertTrue(is_null($array['encrypt_date']));
            $this->assertTrue(is_null($array['encrypt_string_bi']));
            $this->assertTrue(is_null($array['encrypt_integer_bi']));
            $this->assertTrue(is_null($array['encrypt_boolean_bi']));
            $this->assertTrue(is_null($array['encrypt_float_bi']));
            $this->assertTrue(is_null($array['encrypt_date_bi']));
            $this->assertTrue(is_null($array['hash_string']));
            $this->assertTrue(is_null($array['encrypt_float']));
        }
    }

    /**
     * @return void
     */
    public function testBulkSeedWithEmptyStrings_allTypesCorrectViaToArray_Test()
    {
        $models = TestModel::where('name', '=', 'testNullsEmptyStrings')->get();

        $this->assertEquals(1, count($models));

        foreach ($models as $model) {

            $array = $model->toArray();

            $this->assertEquals('', $array['encrypt_string']);
            $this->assertInternalType('string', $array['encrypt_string']);
            $this->assertTrue(is_null($array['encrypt_integer']));
            $this->assertTrue(is_null($array['encrypt_boolean']));
            $this->assertTrue(is_null($array['encrypt_another_boolean']));
            $this->assertTrue(is_null($array['encrypt_date']));
            $this->assertInternalType('string', $array['encrypt_string_bi']);
            $this->assertTrue(is_null($array['encrypt_integer_bi']));
            $this->assertTrue(is_null($array['encrypt_boolean_bi']));
            $this->assertTrue(is_null($array['encrypt_float_bi']));
            $this->assertTrue(is_null($array['encrypt_date_bi']));
            $this->assertEquals('', $array['hash_string']);
            $this->assertInternalType('string', $array['hash_string']);
            $this->assertTrue(is_null($array['encrypt_float']));
        }
    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeForEachValueViaGetProperty_Test()
    {
        $models = TestModel::whereBI(['encrypt_string' => 'uniqueEmail@unique.com'])->Get();

        $this->assertEquals(1, count($models));

        $this->assertEquals(($models->get(0))->name, 'uniqueName123');
        $this->assertEquals(($models->get(0))->encrypt_string, 'uniqueEmail@unique.com');
        $this->assertEquals(($models->get(0))->encrypt_integer, 100);
        $this->assertEquals(($models->get(0))->encrypt_boolean, true);
        $this->assertEquals(($models->get(0))->encrypt_another_boolean, false);
        $this->assertEquals(($models->get(0))->encrypt_float, 10.0001);
        $this->assertEquals(($models->get(0))->encrypt_date, '2043-12-23 23:59:59');
        $this->assertEquals(($models->get(0))->hash_string,
            hash_hmac('sha256', 'A unqiue string that has not been generated',
                env('APP_KEY')));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeOrForEachValueViaGetProperty1_Test()
    {
        $models = TestModel::where(1, 2)->orWhereBI(['encrypt_string' => 'uniqueEmail@unique.com'])->Get();

        $this->assertEquals(1, count($models));

        $this->assertEquals(($models->get(0))->name, 'uniqueName123');
        $this->assertEquals(($models->get(0))->encrypt_string, 'uniqueEmail@unique.com');
        $this->assertEquals(($models->get(0))->encrypt_integer, 100);
        $this->assertEquals(($models->get(0))->encrypt_boolean, true);
        $this->assertEquals(($models->get(0))->encrypt_another_boolean, false);
        $this->assertEquals(($models->get(0))->encrypt_float, 10.0001);
        $this->assertEquals(($models->get(0))->encrypt_date, '2043-12-23 23:59:59');
        $this->assertEquals(($models->get(0))->hash_string,
            hash_hmac('sha256', 'A unqiue string that has not been generated',
                env('APP_KEY')));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeForEachValueViaToArray_Test()
    {
        $models = TestModel::whereBI(['encrypt_string' => 'uniqueEmail@unique.com'])->Get();

        $this->assertEquals(1, count($models));

        $array = ($models->get(0))->toArray();

        $this->assertEquals($array['name'], 'uniqueName123');
        $this->assertEquals($array['encrypt_string'], 'uniqueEmail@unique.com');
        $this->assertEquals($array['encrypt_integer'], 100);
        $this->assertEquals($array['encrypt_boolean'], true);
        $this->assertEquals($array['encrypt_another_boolean'], false);
        $this->assertEquals($array['encrypt_float'], 10.0001);
        $this->assertEquals($array['encrypt_date'], '2043-12-23 23:59:59');
        $this->assertEquals($array['hash_string'], hash_hmac('sha256', 'A unqiue string that has not been generated',
            env('APP_KEY')));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeOrForEachValueViaToArray_Test()
    {
        $models = TestModel::where(1,2)->orWhereBI(['encrypt_string' => 'uniqueEmail@unique.com'])->Get();

        $this->assertEquals(1, count($models));

        $array = ($models->get(0))->toArray();

        $this->assertEquals($array['name'], 'uniqueName123');
        $this->assertEquals($array['encrypt_string'], 'uniqueEmail@unique.com');
        $this->assertEquals($array['encrypt_integer'], 100);
        $this->assertEquals($array['encrypt_boolean'], true);
        $this->assertEquals($array['encrypt_another_boolean'], false);
        $this->assertEquals($array['encrypt_float'], 10.0001);
        $this->assertEquals($array['encrypt_date'], '2043-12-23 23:59:59');
        $this->assertEquals($array['hash_string'], hash_hmac('sha256', 'A unqiue string that has not been generated',
            env('APP_KEY')));

    }


    /**
     * @return void
     */
    public function testSetProperty_existingValuesChanged_Test()
    {
        $models = TestModel::where('name', 'uniqueName123')->Get();

        $this->assertEquals(1, count($models));

        $model = $models->get(0);

        $model->encrypt_string = 'newValue';
        $model->encrypt_integer = 0;
        $model->encrypt_boolean = false;
        $model->encrypt_another_boolean = true;
        $model->encrypt_float = 0;
        $model->encrypt_date = '2012-12-13 13:39:29';
        $model->hash_string = 'Another unqiue string that has not been generated';

        $model->save();

        $models = TestModel::where('name', 'uniqueName123')->Get();

        $this->assertEquals(1, count($models));

        $model = $models->get(0);

        $this->assertEquals($model->encrypt_string, 'newValue');
        $this->assertEquals($model->encrypt_integer, 0);
        $this->assertEquals($model->encrypt_boolean, false);
        $this->assertEquals($model->encrypt_another_boolean, true);
        $this->assertEquals($model->encrypt_float, 0.0);
        $this->assertEquals($model->encrypt_date, '2012-12-13 13:39:29');
        $this->assertEquals($model->hash_string,
            hash_hmac('sha256', 'Another unqiue string that has not been generated',
                env('APP_KEY')));

        $this->assertInternalType('string', $model->encrypt_string);
        $this->assertInternalType('integer', $model->encrypt_integer);
        $this->assertInternalType('boolean', $model->encrypt_boolean);
        $this->assertInternalType('boolean', $model->encrypt_another_boolean);
        $this->assertInternalType('float', $model->encrypt_float);
        $this->assertInstanceOf(Carbon::class, $model->encrypt_date);
        $this->assertInternalType('string', $model->hash_string);

        $this->assertEquals($model->encrypt_string_bi, hash_hmac('sha256', (string)'newValue',
            env('APP_KEY')));
        $this->assertEquals($model->encrypt_integer_bi, hash_hmac('sha256', (string)0,
            env('APP_KEY')));
        $this->assertEquals($model->encrypt_boolean_bi, hash_hmac('sha256', (string)false,
            env('APP_KEY')));
        $this->assertEquals($model->encrypt_float_bi, hash_hmac('sha256', (string)0.0,
            env('APP_KEY')));
        $this->assertEquals($model->encrypt_date_bi, hash_hmac('sha256', (string)'2012-12-13 13:39:29',
            env('APP_KEY')));
    }

    /**
     * @return void
     */
    public function testSetProperty_existingValuesChangedToNull_Test()
    {
        $models = TestModel::where('name', 'uniqueName123')->Get();

        $this->assertEquals(1, count($models));

        $model = $models->get(0);

        $model->encrypt_string = null;
        $model->encrypt_integer = null;
        $model->encrypt_boolean = null;
        $model->encrypt_another_boolean = null;
        $model->encrypt_float = null;
        $model->encrypt_date = null;
        $model->hash_string = null;

        $model->save();

        $models = TestModel::where('name', 'uniqueName123')->Get();

        $this->assertEquals(1, count($models));

        $model = $models->get(0);

        $this->assertEquals($model->encrypt_string, null);
        $this->assertEquals($model->encrypt_integer, null);
        $this->assertEquals($model->encrypt_boolean, null);
        $this->assertEquals($model->encrypt_another_boolean, null);
        $this->assertEquals($model->encrypt_float, null);
        $this->assertEquals($model->encrypt_date, null);
        $this->assertEquals($model->hash_string, null);

        $this->assertEquals($model->encrypt_string_bi, null);
        $this->assertEquals($model->encrypt_integer_bi, null);
        $this->assertEquals($model->encrypt_boolean_bi, null);
        $this->assertEquals($model->encrypt_float_bi, null);
        $this->assertEquals($model->encrypt_date_bi, null);

    }

    /**
     * @return void
     */
    public function testSetProperty_existingValuesChangedToEmptyString_Test()
    {
        $models = TestModel::where('name', 'uniqueName123')->Get();

        $this->assertEquals(1, count($models));

        $model = $models->get(0);

        $model->encrypt_string = '';
        $model->encrypt_integer = 0;
        $model->encrypt_boolean = false;
        $model->encrypt_another_boolean = true;
        $model->encrypt_float = 0;
        $model->encrypt_date = null;
        $model->hash_string = '';

        $model->save();

        $models = TestModel::where('name', 'uniqueName123')->Get();

        $this->assertEquals(1, count($models));

        $model = $models->get(0);

        $this->assertEquals($model->encrypt_string, '');
        $this->assertEquals($model->encrypt_integer, 0);
        $this->assertEquals($model->encrypt_boolean, false);
        $this->assertEquals($model->encrypt_another_boolean, true);
        $this->assertEquals($model->encrypt_float, 0.0);
        $this->assertEquals($model->encrypt_date, null);
        $this->assertEquals($model->hash_string, '');

        $this->assertInternalType('string', $model->encrypt_string);
        $this->assertInternalType('integer', $model->encrypt_integer);
        $this->assertInternalType('boolean', $model->encrypt_boolean);
        $this->assertInternalType('boolean', $model->encrypt_another_boolean);
        $this->assertInternalType('float', $model->encrypt_float);
        $this->assertTrue(is_null($model->encrypt_date));
        $this->assertInternalType('null', $model->encrypt_date);
        $this->assertInternalType('string', $model->hash_string);

        $this->assertEquals($model->encrypt_string_bi, '');
        $this->assertInternalType('string', $model->encrypt_string_bi);
        $this->assertEquals($model->encrypt_integer_bi, hash_hmac('sha256', (string)0,
            env('APP_KEY')));
        $this->assertEquals($model->encrypt_boolean_bi, hash_hmac('sha256', (string)false,
            env('APP_KEY')));
        $this->assertEquals($model->encrypt_float_bi, hash_hmac('sha256', (string)0.0,
            env('APP_KEY')));
        $this->assertEquals($model->encrypt_date_bi, null);
    }


    /**
     * @return void
     */
    public function testUpdate_existingValuesChanged_Test()
    {
        $models = TestModel::where('name', 'uniqueName123')->Get();

        $this->assertEquals(1, count($models));

        $model = $models->get(0);

        $model->update([
            'encrypt_string' => 'newValue',
            'encrypt_integer' => 0,
            'encrypt_boolean' => false,
            'encrypt_another_boolean' => true,
            'encrypt_float' => 0.1,
            'encrypt_date' => '2012-12-13 13:39:29',
            'hash_string' => 'Another unqiue string that has not been generated'
        ]);

        $models = TestModel::where('name', 'uniqueName123')->Get();

        $this->assertEquals(1, count($models));

        $model = $models->get(0);

        $this->assertEquals($model->encrypt_string, 'newValue');
        $this->assertEquals($model->encrypt_integer, 0);
        $this->assertEquals($model->encrypt_boolean, false);
        $this->assertEquals($model->encrypt_another_boolean, true);
        $this->assertEquals($model->encrypt_float, 0.1);
        $this->assertEquals($model->encrypt_date, '2012-12-13 13:39:29');
        $this->assertEquals($model->hash_string,
            hash_hmac('sha256', 'Another unqiue string that has not been generated',
                env('APP_KEY')));

        $this->assertInternalType('string', $model->encrypt_string);
        $this->assertInternalType('integer', $model->encrypt_integer);
        $this->assertInternalType('boolean', $model->encrypt_boolean);
        $this->assertInternalType('boolean', $model->encrypt_another_boolean);
        $this->assertInternalType('float', $model->encrypt_float);
        $this->assertInstanceOf(Carbon::class, $model->encrypt_date);
        $this->assertInternalType('string', $model->hash_string);

        $this->assertEquals($model->encrypt_string_bi, hash_hmac('sha256', (string)'newValue',
            env('APP_KEY')));
        $this->assertEquals($model->encrypt_integer_bi, hash_hmac('sha256', (string)0,
            env('APP_KEY')));
        $this->assertEquals($model->encrypt_boolean_bi, hash_hmac('sha256', (string)false,
            env('APP_KEY')));
        $this->assertEquals($model->encrypt_float_bi, hash_hmac('sha256', (string)0.1,
            env('APP_KEY')));
        $this->assertEquals($model->encrypt_date_bi, hash_hmac('sha256', (string)'2012-12-13 13:39:29',
            env('APP_KEY')));
    }

    /**
     * @return void
     */
    public function testUpdate_existingValuesChangedToNull_Test()
    {
        $models = TestModel::where('name', 'uniqueName123')->Get();

        $this->assertEquals(1, count($models));

        $model = $models->get(0);

        $model->update([
            'encrypt_string' => null,
            'encrypt_integer' => null,
            'encrypt_boolean' => null,
            'encrypt_another_boolean' => null,
            'encrypt_float' => null,
            'encrypt_date' => null,
            'hash_string' => null
        ]);

        $models = TestModel::where('name', 'uniqueName123')->Get();

        $this->assertEquals(1, count($models));

        $model = $models->get(0);

        $this->assertEquals($model->encrypt_string, null);
        $this->assertEquals($model->encrypt_integer, null);
        $this->assertEquals($model->encrypt_boolean, null);
        $this->assertEquals($model->encrypt_another_boolean, null);
        $this->assertEquals($model->encrypt_float, null);
        $this->assertEquals($model->encrypt_date, null);
        $this->assertEquals($model->hash_string, null);

        $this->assertEquals($model->encrypt_string_bi, null);
        $this->assertEquals($model->encrypt_integer_bi, null);
        $this->assertEquals($model->encrypt_boolean_bi, null);
        $this->assertEquals($model->encrypt_float_bi, null);
        $this->assertEquals($model->encrypt_date_bi, null);

    }

    /**
     * @return void
     */
    public function testUpdate_existingValuesChangedToEmptyString_Test()
    {
        $models = TestModel::where('name', 'uniqueName123')->Get();

        $this->assertEquals(1, count($models));

        $model = $models->get(0);

        $model->update([
            'encrypt_string' => '',
            'encrypt_integer' => 0,
            'encrypt_boolean' => false,
            'encrypt_another_boolean' => true,
            'encrypt_float' => 0,
            'encrypt_date' => null,
            'hash_string' => ''
        ]);

        $models = TestModel::where('name', 'uniqueName123')->Get();

        $this->assertEquals(1, count($models));

        $model = $models->get(0);

        $this->assertEquals($model->encrypt_string, '');
        $this->assertEquals($model->encrypt_integer, 0);
        $this->assertEquals($model->encrypt_boolean, false);
        $this->assertEquals($model->encrypt_another_boolean, true);
        $this->assertEquals($model->encrypt_float, 0.0);
        $this->assertEquals($model->encrypt_date, null);
        $this->assertEquals($model->hash_string, '');

        $this->assertInternalType('string', $model->encrypt_string);
        $this->assertInternalType('integer', $model->encrypt_integer);
        $this->assertInternalType('boolean', $model->encrypt_boolean);
        $this->assertInternalType('boolean', $model->encrypt_another_boolean);
        $this->assertInternalType('float', $model->encrypt_float);
        $this->assertTrue(is_null($model->encrypt_date));
        $this->assertInternalType('null', $model->encrypt_date);
        $this->assertInternalType('string', $model->hash_string);

        $this->assertEquals($model->encrypt_string_bi, '');
        $this->assertInternalType('string', $model->encrypt_string_bi);
        $this->assertEquals($model->encrypt_integer_bi, hash_hmac('sha256', (string)0,
            env('APP_KEY')));
        $this->assertEquals($model->encrypt_boolean_bi, hash_hmac('sha256', (string)false,
            env('APP_KEY')));
        $this->assertEquals($model->encrypt_float_bi, hash_hmac('sha256', (string)0.0,
            env('APP_KEY')));
        $this->assertEquals($model->encrypt_date_bi, null);
    }

    /**
     * @return void
     */
    public function testUpdate_existingValuesNotChanged_Test()
    {
        $model = TestModel::where('name', 'uniqueName123')->first();
        $model->encrypt_string = "test";
        $model->save();
        $firstEncryption = $model->getOriginal("encrypt_string");

        $model = TestModel::where('name', 'uniqueName123')->first();
        $model->update([
            'encrypt_string' => "test"
        ]);
        $secondEncryption = $model->getOriginal("encrypt_string");

        $this->assertEquals($firstEncryption, $secondEncryption, "The value didn't change but the encrypted one did");
    }

    /**
     * @return void
     */
    public function testTypes_checkValidDateFormat_Test()
    {
        $this->expectException(\InvalidArgumentException::class);

        factory(\Tests\Fixtures\Models\TestModel::class, 1)->create([
            'name' => 'testNulls',
            'encrypt_string' => null,
            'encrypt_integer' => null,
            'encrypt_boolean' => null,
            'encrypt_another_boolean' => null,
            'encrypt_float' => null,
            'encrypt_date' => '24/11/1972 12:23',
            'hash_string' => null
        ]);
    }

    /**
     * @return void
     */
    public function testTypes_checkValidDateFormatNotEmptyString_Test()
    {
        $this->expectException(\TypeError::class);

        factory(\Tests\Fixtures\Models\TestModel::class, 1)->create([
            'name' => 'testNulls',
            'encrypt_string' => null,
            'encrypt_integer' => null,
            'encrypt_boolean' => null,
            'encrypt_another_boolean' => null,
            'encrypt_float' => null,
            'encrypt_date' => '',
            'hash_string' => null
        ]);
    }

    /**
     * @return void
     */
    public function testTypes_checkStrings_Test()
    {
        $this->expectException(\TypeError::class);

        factory(\Tests\Fixtures\Models\TestModel::class, 1)->create([
            'name' => 'testNulls',
            'encrypt_string' => 1,
            'encrypt_integer' => null,
            'encrypt_boolean' => null,
            'encrypt_another_boolean' => null,
            'encrypt_float' => null,
            'encrypt_date' => null,
            'hash_string' => null
        ]);
    }

    /**
     * @return void
     */
    public function testTypes_checkIntegersWithFloat_Test()
    {
        $this->expectException(\TypeError::class);

        factory(\Tests\Fixtures\Models\TestModel::class, 1)->create([
            'name' => 'testNulls',
            'encrypt_string' => null,
            'encrypt_integer' => 1.0,
            'encrypt_boolean' => null,
            'encrypt_another_boolean' => null,
            'encrypt_float' => null,
            'encrypt_date' => null,
            'hash_string' => null
        ]);
    }

    /**
     * @return void
     */
    public function testTypes_checkIntegersWithString_Test()
    {
        $this->expectException(\TypeError::class);

        factory(\Tests\Fixtures\Models\TestModel::class, 1)->create([
            'name' => 'testNulls',
            'encrypt_string' => null,
            'encrypt_integer' => 'a',
            'encrypt_boolean' => null,
            'encrypt_another_boolean' => null,
            'encrypt_float' => null,
            'encrypt_date' => null,
            'hash_string' => null
        ]);
    }

    /**
     * @return void
     */
    public function testTypes_checkBooleanWithInt_Test()
    {
        $this->expectException(\TypeError::class);

        factory(\Tests\Fixtures\Models\TestModel::class, 1)->create([
            'name' => 'testNulls',
            'encrypt_string' => null,
            'encrypt_integer' => null,
            'encrypt_boolean' => 1,
            'encrypt_another_boolean' => null,
            'encrypt_float' => null,
            'encrypt_date' => null,
            'hash_string' => null
        ]);
    }

    /**
     * @return void
     */
    public function testTypes_checkBooleanWithString_Test()
    {
        $this->expectException(\TypeError::class);

        factory(\Tests\Fixtures\Models\TestModel::class, 1)->create([
            'name' => 'testNulls',
            'encrypt_string' => null,
            'encrypt_integer' => null,
            'encrypt_boolean' => null,
            'encrypt_another_boolean' => 'a',
            'encrypt_float' => null,
            'encrypt_date' => null,
            'hash_string' => null
        ]);
    }

    /**
     * @return void
     */
    public function testTypes_checkFloatWithString_Test()
    {
        $this->expectException(\TypeError::class);

        factory(\Tests\Fixtures\Models\TestModel::class, 1)->create([
            'name' => 'testNulls',
            'encrypt_string' => null,
            'encrypt_integer' => null,
            'encrypt_boolean' => null,
            'encrypt_another_boolean' => null,
            'encrypt_float' => 'a',
            'encrypt_date' => null,
            'hash_string' => null
        ]);
    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeStringNull_Test()
    {
        $models = TestModel::whereBI(['encrypt_string' => null])->Get();

        $this->assertEquals(1, count($models));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeOrStringNull_Test()
    {
        $models = TestModel::where(1,2)->orWhereBI(['encrypt_string' => null])->Get();

        $this->assertEquals(1, count($models));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeStringEmpty_Test()
    {
        $models = TestModel::whereBI(['encrypt_string' => ''])->Get();

        $this->assertEquals(1, count($models));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeOrStringEmpty_Test()
    {
        $models = TestModel::where(1,2)->orWhereBI(['encrypt_string' => ''])->Get();

        $this->assertEquals(1, count($models));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeDateNull_Test()
    {
        $models = TestModel::whereBI(['encrypt_date' => null])->Get();

        $this->assertEquals(2, count($models));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeOrDateNull_Test()
    {
        $models = TestModel::where(1,2)->orWhereBI(['encrypt_date' => null])->Get();

        $this->assertEquals(2, count($models));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeDateSet_Test()
    {
        $models = TestModel::whereBI(['encrypt_date' => '2023-12-23 23:59:59'])->Get();

        $this->assertEquals(1, count($models));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeOrDateSet_Test()
    {
        $models = TestModel::where(1,2)->orWhereBI(['encrypt_date' => '2023-12-23 23:59:59'])->Get();

        $this->assertEquals(1, count($models));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeIntegerNull_Test()
    {
        $models = TestModel::whereBI(['encrypt_integer' => null])->Get();

        $this->assertEquals(2, count($models));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeOrIntegerNull_Test()
    {
        $models = TestModel::where(1,2)->orWhereBI(['encrypt_integer' => null])->Get();

        $this->assertEquals(2, count($models));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeIntegerSet_Test()
    {
        $models = TestModel::whereBI(['encrypt_integer' => 100])->Get();

        $this->assertEquals(1, count($models));

    }


    /**
     * @return void
     */
    public function testBulkSeed_localScopeOrIntegerSet_Test()
    {
        $models = TestModel::where(1,2)->orWhereBI(['encrypt_integer' => 100])->Get();

        $this->assertEquals(1, count($models));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeBooleanNull_Test()
    {
        $models = TestModel::whereBI(['encrypt_boolean' => null])->Get();

        $this->assertEquals(2, count($models));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeOrBooleanNull_Test()
    {
        $models = TestModel::where(1,2)->orWhereBI(['encrypt_boolean' => null])->Get();

        $this->assertEquals(2, count($models));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeBooleanSet_Test()
    {
        $models = TestModel::whereBI(['encrypt_boolean' => true])->Get();

        $total = DB::table('test_tables')->where('encrypt_boolean_bi',
            hash_hmac('sha256', (string)true, env('APP_KEY')))->count();

        $this->assertEquals($total, count($models));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeOrBooleanSet_Test()
    {
        $models = TestModel::where(1,2)->orWhereBI(['encrypt_boolean' => true])->Get();

        $total = DB::table('test_tables')->where('encrypt_boolean_bi',
            hash_hmac('sha256', (string)true, env('APP_KEY')))->count();

        $this->assertEquals($total, count($models));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeFloatNull_Test()
    {
        $models = TestModel::whereBI(['encrypt_float' => null])->Get();

        $this->assertEquals(2, count($models));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeOrFloatNull_Test()
    {
        $models = TestModel::where(1,2)->orWhereBI(['encrypt_float' => null])->Get();

        $this->assertEquals(2, count($models));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeFloatSet_Test()
    {
        $models = TestModel::whereBI(['encrypt_float' => 10.0001])->Get();

        $this->assertEquals(1, count($models));

    }

    /**
     * @return void
     */
    public function testBulkSeed_localScopeOrFloatSet_Test()
    {
        $models = TestModel::where(1,2)->orWhereBI(['encrypt_float' => 10.0001])->Get();

        $this->assertEquals(1, count($models));

    }

    public function testConstruct_valuesAndTypesCorrectBeforeAndAfterSave_Test()
    {
        $model = new TestModel([
            'name' => 'uniqueName129',
            'encrypt_string' => 'uniqueEmail@test.com',
            'encrypt_integer' => 100,
            'encrypt_boolean' => false,
            'encrypt_another_boolean' => true,
            'encrypt_float' => 9999.999,
            'encrypt_date' => '2043-12-23 23:59:59',
            'hash_string' => 'A unqiue string that has not been generated again'
        ]);

        $array = $model->toArray();

        $this->assertEquals($array['name'], 'uniqueName129');
        $this->assertEquals($array['encrypt_string'], 'uniqueEmail@test.com');
        $this->assertEquals($array['encrypt_integer'], 100);
        $this->assertEquals($array['encrypt_boolean'], false);
        $this->assertEquals($array['encrypt_another_boolean'], true);
        $this->assertEquals($array['encrypt_float'], 9999.999);
        $this->assertEquals($array['encrypt_date'], '2043-12-23 23:59:59');
        $this->assertEquals($array['hash_string'], hash_hmac('sha256', 'A unqiue string that has not been generated again',
            env('APP_KEY')));

        $model->save();

        $array = $model->toArray();

        $this->assertEquals($array['name'], 'uniqueName129');
        $this->assertEquals($array['encrypt_string'], 'uniqueEmail@test.com');
        $this->assertEquals($array['encrypt_integer'], 100);
        $this->assertEquals($array['encrypt_boolean'], false);
        $this->assertEquals($array['encrypt_another_boolean'], true);
        $this->assertEquals($array['encrypt_float'], 9999.999);
        $this->assertEquals($array['encrypt_date'], '2043-12-23 23:59:59');
        $this->assertEquals($array['hash_string'], hash_hmac('sha256', 'A unqiue string that has not been generated again',
            env('APP_KEY')));

        $models = TestModel::whereBI(['encrypt_float' => 9999.999])->Get();
        $this->assertEquals(1, count($models));
    }


    /**
     *
     */
    public function testDelete_findBylocalScopeFloat_Test()
    {
        $models = TestModel::whereBI(['encrypt_float' => 10.0001])->Get();

        $this->assertEquals(1, count($models));

        $model = $models->get(0);

        $model->delete();

        $models = TestModel::whereBI(['encrypt_float' => 10.0001])->Get();

        $this->assertEquals(0, count($models));
    }

    /**
     *
     */
    public function testDelete_findBylocalScopeOrFloat_Test()
    {
        $models = TestModel::where(1,2)->orWhereBI(['encrypt_float' => 20.0001])->Get();

        $this->assertEquals(1, count($models));

        $model = $models->get(0);

        $model->delete();

        $models = TestModel::where(1,2)->orWhereBI(['encrypt_float' => 20.0001])->Get();

        $this->assertEquals(0, count($models));
    }

}
