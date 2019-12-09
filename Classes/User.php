<?php 

class User extends Model {

    protected $appends = ['fullName'];

    protected $hidden = ['password'];

    protected $tableName = 'Users';

    protected $autoIncrement = false;

    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getPassword()
    {   
        return $this->attributes['password'];
    }

    public function setFullName($value)
    {   
        $data = explode(' ', $value);

        $this->firstName = strVal(array_shift($data));
        $this->lastName = implode(' ', $data);

    }

    public function setPassword($attributeValue)
    {   
        $this->attributes['password'] = $attributeValue;
    }

}