# Laravel model encryption, hashing and blind index
A no fuss Laravel model trait to add encrypted model attributes with the option of blind indexes and hashed attributes.

Model attributes that are marked as encrypted will be encrypted via Laravel's default encryption. Simply set the model attribute with a value of string, integer, float, boolean or date (as a string) and the value will be persisted as the encrypted value. Accessing the attribute will decrypt the attribute and cast it to it's defined type (a Carbon instance if date).

You may also specify a blind index (BI) attribute that is associated with encrypted attribute. Blind index attributes persist the hashed value of the associated plain text encrypted attribute column. BI attributes can be used for searching the database where search values are encrypted. A 'whereBI' local scope has been added that allows 'where' clauses to be specified using the encrypted attribute name and plain text value.

Finally, you may specify hashed attributes. These are simply hashed before persistence and cannot be converted to plain text on retrieval.

Note - adding a blind index will reduce the security of your encrypted columns as the associated BI is deterministic and will identify collisions in your encrypted data. This is by design, without it your encrypted data would not be searchable.

Note - you may change the hashing algorithm by setting the $hashAlg property on your model, this is set to 'sha256' by default. if you increase the security of the algorithm used then you may have to increase the column size for BI columns. I have found that length 64 works well for sha256.

Note - this trait does not create DB columns for you. You must create your own migrations. Please see Fixtures/Migrations/2018_04_18_134800_create_test_table.php for an idea of column definitions that fit with the attributes defined within Fixtures/Models/TestModel.php

# Requirements
This package requires Laravel 5.6. Earlier versions of Laravel have a different model implementation that is not compatible with this package.

# Installation
```
composer require rbennett/laravel-model-encryption
```

# How to use
##Add the trait to your model
```
use RBennett\ModelEncryption\HasEncryptedAttributes;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use HasEncryptedAttributes;
```

## Add Encrypted database columns
For any DB columns that should be encrypted / decrypted add them to the $encrypted property of the model.
This should be set to an array of form:

['actual_column_name' => ['type' => 'string|integer|float|boolean|date', {'hasBlindIndex' => 'blind_index_column_name}]]

Note - hasBlindIndex is optional
Note - if adding a date then specifiy 'dateformat'

See example below:

```
protected $encrypted = [
        'encrypt_string' =>
            ['type' => 'string', 'hasBlindIndex' => 'encrypt_string_bi'],
        'encrypt_integer' =>
            ['type' => 'integer', 'hasBlindIndex' => 'encrypt_integer_bi'],
        'encrypt_boolean' =>
            ['type' => 'boolean', 'hasBlindIndex' => 'encrypt_boolean_bi'],
        'encrypt_another_boolean' =>
            ['type' => 'boolean'],
        'encrypt_float' =>
            ['type' => 'float', 'hasBlindIndex' => 'encrypt_float_bi'],
        'encrypt_date' =>
            ['type' => 'date', 'hasBlindIndex' => 'encrypt_date_bi', 'dateFormat' => 'Y-m-d H:i:s']
    ];
```

## Add Hashed database columns

For any DB columns that are to be hashed, simply add them to the $hashed property of the model:

```
protected $hashed = ['column_name1', 'column_name2];
```

## Local scope helper
A local scope has been added that allows you to query BI columns (whereBI and orWhereBI). When using whereBI or orWhereBI, pass in an array where the key is the encrypted column name (it will resolve the associated BI column for you) and the value you want to search for. The value should be passed in as plain text in one of the following types:

string, integer, float, boolean or date (as a string)

When querying for dates ensure that the date format is identical to the dateformat set within the $encrypted property.
