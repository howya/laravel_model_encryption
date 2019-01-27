<?php

namespace Tests\Fixtures\Seeds;

use Tests\Fixtures\Models\TestModel;

class TestSeederTable1 extends \Illuminate\Database\Seeder
{
    public function run()
    {
        factory(TestModel::class, 1)->create([
            'name' => 'testNulls',
            'encrypt_string' => null,
            'encrypt_integer' => null,
            'encrypt_boolean' => null,
            'encrypt_another_boolean' => null,
            'encrypt_float' => null,
            'encrypt_date' => null,
            'hash_string' => null
        ]);

        factory(TestModel::class, 1)->create([
            'name' => 'testNullsEmptyStrings',
            'encrypt_string' => '',
            'encrypt_integer' => null,
            'encrypt_boolean' => null,
            'encrypt_another_boolean' => null,
            'encrypt_float' => null,
            'encrypt_date' => null,
            'hash_string' => ''
        ]);

        factory(TestModel::class, 1)->create([
            'name' => 'zeroValues',
            'encrypt_string' => 'zeroValues@unique.com',
            'encrypt_integer' => 0,
            'encrypt_boolean' => true,
            'encrypt_another_boolean' => false,
            'encrypt_float' => 0,
            'encrypt_date' => '2023-12-23 23:59:59',
            'hash_string' => 'A unqiue string that has not been generated'
        ]);

        factory(TestModel::class, 1)->create([
            'name' => 'uniqueName123',
            'encrypt_string' => 'uniqueEmail@unique.com',
            'encrypt_integer' => 100,
            'encrypt_boolean' => true,
            'encrypt_another_boolean' => false,
            'encrypt_float' => 10.0001,
            'encrypt_date' => '2043-12-23 23:59:59',
            'hash_string' => 'A unqiue string that has not been generated'
        ]);

        factory(TestModel::class, 1)->create([
            'name' => 'uniqueOrName123',
            'encrypt_string' => 'uniqueOrEmail@unique.com',
            'encrypt_integer' => 200,
            'encrypt_boolean' => true,
            'encrypt_another_boolean' => false,
            'encrypt_float' => 20.0001,
            'encrypt_date' => '2043-12-23 23:59:59',
            'hash_string' => 'A unqiue string that has not been generated for or'
        ]);

        factory(TestModel::class, 500)->create();
    }
}
