<?php

namespace Tests\Fixtures\Models;

use App\Support\Models\Traits\HasEncryptedAttributes;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use HasEncryptedAttributes;

    protected $table = 'test_tables';

    protected $fillable = [
        'name',
        'encrypt_string',
        'encrypt_integer',
        'encrypt_boolean',
        'encrypt_float',
        'encrypt_date',
        'hash_string'
    ];

    protected $encrypted = [
        'encrypt_string' =>
            ['type' => 'string', 'hasBlindIndex' => 'encrypt_string_bi'],
        'encrypt_integer' =>
            ['type' => 'integer', 'hasBlindIndex' => 'encrypt_integer_bi'],
        'encrypt_boolean' =>
            ['type' => 'boolean', 'hasBlindIndex' => 'encrypt_boolean_bi'],
        'encrypt_boolean2' =>
            ['type' => 'boolean'],
        'encrypt_float' =>
            ['type' => 'float', 'hasBlindIndex' => 'encrypt_float_bi'],
        'encrypt_date' =>
            ['type' => 'date', 'hasBlindIndex' => 'encrypt_date_bi', 'dateFormat' => 'Y-m-d H:i:s']
    ];

    protected $hashed = ['hash_string'];

}
