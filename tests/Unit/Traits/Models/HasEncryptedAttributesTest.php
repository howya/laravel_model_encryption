<?php

namespace Tests\Traits\Models;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
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

        $this->app->make(EloquentFactory::class)->load(__DIR__ . '/../../../Fixtures/Factories');
    }

    /**
     * @return void
     */
    public function testBulkSeedWithoutNulls_allTypesCorrectViaGetProperty_Test()
    {
        Artisan::call('migrate:fresh', ['--path' => '/tests/Fixtures/Migrations']);
        Artisan::call('db:seed', ['--class' => 'Tests\Fixtures\Seeds\TestDatabaseSeeder']);

        $models = TestModel::where('name', '!=', 'testNulls')->where('name', '!=', 'testNullsEmptyStrings')->get();

        $this->assertEquals(1002, count($models));

        foreach ($models as $model) {

            $this->assertInternalType('string', $model->name);
            $this->assertInternalType('string', $model->encrypt_string);
            $this->assertInternalType('integer', $model->encrypt_integer);
            $this->assertInternalType('boolean', $model->encrypt_boolean);
            $this->assertInternalType('boolean', $model->encrypt_boolean2);
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
            $this->assertTrue(is_null($model->encrypt_boolean2));
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
            $this->assertTrue(is_null($model->encrypt_boolean2));
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

        $this->assertEquals(1002, count($models));

        foreach ($models as $model) {

            $array = $model->toArray();

            $this->assertInternalType('string', $array['name']);
            $this->assertInternalType('string', $array['encrypt_string']);
            $this->assertInternalType('integer', $array['encrypt_integer']);
            $this->assertInternalType('boolean', $array['encrypt_boolean']);
            $this->assertInternalType('boolean', $array['encrypt_boolean2']);
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
            $this->assertTrue(is_null($array['encrypt_boolean2']));
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
            $this->assertTrue(is_null($array['encrypt_boolean2']));
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
        $this->assertEquals(($models->get(0))->encrypt_boolean2, false);
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
        $this->assertEquals($array['encrypt_boolean2'], false);
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
        $model->encrypt_boolean2 = true;
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
        $this->assertEquals($model->encrypt_boolean2, true);
        $this->assertEquals($model->encrypt_float, 0.0);
        $this->assertEquals($model->encrypt_date, '2012-12-13 13:39:29');
        $this->assertEquals($model->hash_string,
            hash_hmac('sha256', 'Another unqiue string that has not been generated',
                env('APP_KEY')));

        $this->assertInternalType('string', $model->encrypt_string);
        $this->assertInternalType('integer', $model->encrypt_integer);
        $this->assertInternalType('boolean', $model->encrypt_boolean);
        $this->assertInternalType('boolean', $model->encrypt_boolean2);
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
        $model->encrypt_boolean2 = null;
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
        $this->assertEquals($model->encrypt_boolean2, null);
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
        $model->encrypt_boolean2 = true;
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
        $this->assertEquals($model->encrypt_boolean2, true);
        $this->assertEquals($model->encrypt_float, 0.0);
        $this->assertEquals($model->encrypt_date, null);
        $this->assertEquals($model->hash_string, '');

        $this->assertInternalType('string', $model->encrypt_string);
        $this->assertInternalType('integer', $model->encrypt_integer);
        $this->assertInternalType('boolean', $model->encrypt_boolean);
        $this->assertInternalType('boolean', $model->encrypt_boolean2);
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
    public function testTypes_checkValidDateFormat_Test()
    {
        $this->expectException(\InvalidArgumentException::class);

        factory(\Tests\Fixtures\Models\TestModel::class, 1)->create([
            'name' => 'testNulls',
            'encrypt_string' => null,
            'encrypt_integer' => null,
            'encrypt_boolean' => null,
            'encrypt_boolean2' => null,
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
            'encrypt_boolean2' => null,
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
            'encrypt_boolean2' => null,
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
            'encrypt_boolean2' => null,
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
            'encrypt_boolean2' => null,
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
            'encrypt_boolean2' => null,
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
            'encrypt_boolean2' => 'a',
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
            'encrypt_boolean2' => null,
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
        Artisan::call('migrate:fresh', ['--path' => '/tests/Fixtures/Migrations']);
        Artisan::call('db:seed', ['--class' => 'Tests\Fixtures\Seeds\TestDatabaseSeeder']);

        $models = TestModel::whereBI(['encrypt_string' => null])->Get();

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
    public function testBulkSeed_localScopeDateNull_Test()
    {
        $models = TestModel::whereBI(['encrypt_date' => null])->Get();

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
    public function testBulkSeed_localScopeIntegerNull_Test()
    {
        $models = TestModel::whereBI(['encrypt_integer' => null])->Get();

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
    public function testBulkSeed_localScopeBooleanNull_Test()
    {
        $models = TestModel::whereBI(['encrypt_boolean' => null])->Get();

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
    public function testBulkSeed_localScopeFloatNull_Test()
    {
        $models = TestModel::whereBI(['encrypt_float' => null])->Get();

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
}
