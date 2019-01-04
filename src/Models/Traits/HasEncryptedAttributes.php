<?php

namespace Rbennett\ModelEncryption;

use Carbon\Carbon;
use TypeError;

trait HasEncryptedAttributes
{
    /**
     * Indicate which model attributes are to be encrypted, each attribute must specify
     * type via the type key. Supported types are:
     *
     * integer, boolean, float, string, date
     *
     * For date types you may add a date format string (used to instantiate Carbon date object
     * from decrypted (string) value) via the dateFormat key
     *
     * Each attribute may also indicate if it is accompanied by a blind index (repeatable
     * hash of the pre-encrypted value) that can be used for searching. Blind index columns
     * must be created then named with the HasBlindIndex key
     *
     * @var array
     */
    /*
    protected $encrypted = [
        'contact_email' => [
            'type' => 'string',
            'hasBlindIndex' => 'contact_email_bi'
        ]
    ];

    OR

    protected $encrypted = [
        'first_logged_on' => [
            'type' => 'date',
            'hasBlindIndex' => 'first_logged_on_bi',
            'dateFormat' => 'Y-m-d H'
        ]
    ];
    */

    /**
     * Indicate which model attributes are to be hashed via hash_hmac sha256 using app key.
     * Please note, this hashing is meant for searchable hashes and will produce the
     * same hash for the same value - NOT secure for passwords - use BCRYPT instead
     *
     *
     * @var array
     */
    //protected $hashed = ['username'];

    /**
     * @var string
     */
    public $hashAlg = 'sha256';


    /**
     * @var array
     */
    private $primitiveTypes = ['integer', 'boolean', 'float', 'string'];


    /**
     * @param $key
     * @param $value
     * @return string
     */
    private function setEncryptedHashedBIAttributes($key, $value){

        if (array_key_exists($key, $this->encrypted)) {

            $this->checkTypes($key, $value);
            $this->setBlindIndex($key, $value);
            $value = $this->setEncrypted($value);
        }

        return $this->setHashed($key, $value);
    }


    /**
     * @param $value
     * @return string
     */
    private function setEncrypted($value){
        if (!is_null($value)) {
            $value = encrypt((string)$value);
        }
        return $value;
    }


    /**
     * @param $key
     * @param $value
     * @return string
     */
    private function setHashed($key, $value){

        if (in_array($key, $this->hashed) && !is_null($value) && '' != $value) {
            $value = $this->getHash($value);
        }

        return $value;
    }


    /**
     * @param $key
     * @param $originalValue
     */
    private function setBlindIndex($key, $originalValue)
    {
        if (array_key_exists('hasBlindIndex', $this->encrypted[$key]) && $this->encrypted[$key]['hasBlindIndex']) {

            if (is_null($originalValue)) {
                $this->{$this->encrypted[$key]['hasBlindIndex']} = null;

            } else if ('' === $originalValue) {
                    $this->{$this->encrypted[$key]['hasBlindIndex']} = '';

            } else {
                $this->{$this->encrypted[$key]['hasBlindIndex']} = $this->getHash($originalValue);
            }
        }
    }


    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    private function getDecryptIfEncrypted($key, $value)
    {
        if (array_key_exists($key, $this->encrypted) && !empty($value)) {

            $decryptionType = array_key_exists('type', $this->encrypted[$key]) ? $this->encrypted[$key]['type'] : 'string';

            if ($decryptionType == 'date') {
                return $this->castDate(decrypt($value),array_key_exists('dateFormat', $this->encrypted[$key]) ? $this->encrypted[$key]['dateFormat'] : null);

            } else {
                return $this->castPrimitive(decrypt($value), $decryptionType);
            }
        }

        return $value;
    }


    /**
     * @param $value
     * @param $type
     * @return mixed
     */
    private function castPrimitive($value, $type)
    {
        if (in_array($type, $this->primitiveTypes)) {
            settype($value, $type);
            return $value;
        }

        return (string)$value;
    }


    /**
     * @param $value
     * @param null $format
     * @return mixed
     */
    private function castDate($value, $format = null)
    {
        if (!is_null($value) && $value !== '') {
            return $format ? Carbon::createFromFormat($format, $value) : Carbon::parse($value);
        } else {
            throw new TypeError("Encryption error, date cannot be empty string");
        }
    }


    /**
     * @param $originalValue
     * @return string
     */
    private function getHash($originalValue)
    {
        return hash_hmac($this->hashAlg, (string)$originalValue, env('APP_KEY'));
    }


    /**
     * @param $key
     * @param $value
     * @return bool|mixed
     * @throws TypeError
     */
    private function checkTypes($key, $value)
    {
        if (array_key_exists($key, $this->encrypted) && !is_null($value)) {

            $type = array_key_exists('type', $this->encrypted[$key]) ? $this->encrypted[$key]['type'] : 'string';
            $format = array_key_exists('dateFormat', $this->encrypted[$key]) ? $this->encrypted[$key]['dateFormat'] : null;

            switch($type) {
                case 'date':
                    $this->castDate($value, $format);
                    break;
                case 'float':
                    $this->checkType($key, $value, ['float', 'integer', 'double']);
                    break;
                default:
                    if(!in_array($type, $this->primitiveTypes)) throw new TypeError("Encryption error, $type not a supported type of 'integer', 'boolean', 'float', 'string', 'date'");
                    $this->checkType($key, $value, [$type]);
            }

        }

        return true;
    }

    /**
     * @param $key
     * @param $value
     * @param array $types
     * @return bool
     */
    private function checkType($key, $value, array $types){

        $hasMatchedType = false;

        foreach($types as $type){
            if (gettype($value) == $type){
                $hasMatchedType = true;
                break;
            }
        }

        if (!$hasMatchedType){
            throw new TypeError("Encryption error, $key not of correct type or null. Type is: " . gettype($value));
        }

        return true;
    }


    /**
     * @param $query
     * @param array $blindIndexQuery
     * @return mixed
     * @throws \Exception
     */
    public function scopeWhereBI($query, array $blindIndexQuery)
    {
        if (array_key_exists(key($blindIndexQuery), $this->encrypted) && array_key_exists('hasBlindIndex', $this->encrypted[key($blindIndexQuery)])) {

            $columnToSearch = $this->encrypted[key($blindIndexQuery)]['hasBlindIndex'];
            $unHashedValueToSearch = $blindIndexQuery[key($blindIndexQuery)];

            if (is_null($unHashedValueToSearch) || '' === $unHashedValueToSearch) {
                return $query->where($columnToSearch, $unHashedValueToSearch);
            } else {
                return $query->where($columnToSearch, $this->getHash($unHashedValueToSearch));
            }
        }

        throw new \Exception("Blind index column for " . key($blindIndexQuery) . " not found");
    }


    /**
     * @param $query
     * @param array $blindIndexQuery
     * @return mixed
     * @throws \Exception
     */
    public function scopeOrWhereBI($query, array $blindIndexQuery)
    {
        if (array_key_exists(key($blindIndexQuery), $this->encrypted) && array_key_exists('hasBlindIndex', $this->encrypted[key($blindIndexQuery)])) {

            $columnToSearch = $this->encrypted[key($blindIndexQuery)]['hasBlindIndex'];
            $unHashedValueToSearch = $blindIndexQuery[key($blindIndexQuery)];

            if (is_null($unHashedValueToSearch) || '' === $unHashedValueToSearch) {
                return $query->orWhere($columnToSearch, $unHashedValueToSearch);
            } else {
                return $query->orWhere($columnToSearch, $this->getHash($unHashedValueToSearch));
            }
        }

        throw new \Exception("Blind index column for " . key($blindIndexQuery) . " not found");
    }


    //
    //Eloquent Model method overrides below
    //


    /**
     * @param $key
     * @return mixed
     */
    protected function getAttributeFromArray($key){
        return $this->getDecryptIfEncrypted($key, parent::getAttributeFromArray($key));
    }



    /**
     * @return array
     */
    protected function getArrayableAttributes()
    {
        $array = parent::getArrayableAttributes();

        foreach ($array as $key => $attribute) {
            $array[$key] = $this->getDecryptIfEncrypted($key, $attribute);
        }

        return $array;
    }



    /**
     * @param $key
     * @param $value
     */
    public function setAttribute($key, $value)
    {
        parent::setAttribute($key, $this->setEncryptedHashedBIAttributes($key, $value));
    }


    /**
     * List all encripted columns so we can do some magic after
     * @return array
     */
    public function encriptedColumns(){
      return (!empty($this->encrypted))? $this->encripted : [];
    }

}
