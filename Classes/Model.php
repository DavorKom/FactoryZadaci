<?php

class Model {

    protected $attributes = [];

    protected $original = [];

    protected $appends = []; 

    protected $hidden = [];

    protected static $query;

    public static function setDBQueryBuilder($queryBuilder) 
    {
        static::$query = $queryBuilder;
    }

    public function __get($attributeName) 
    {
        $method = 'get' . ucfirst($attributeName);

        if(method_exists($this, $method))
        {
            return $this->$method();
        }

        if(array_key_exists($attributeName, $this->attributes)) 
        {

            return $this->attributes[$attributeName];

        }
    
    }

    public function __set($attributeName, $attributeValue) 
    {
        $method = 'set' . ucfirst($attributeName);

        if(method_exists($this, $method))
        {
            return $this->$method($attributeValue);
        }

        $this->attributes[$attributeName] = $attributeValue;

    }

    protected function removeHiddenKeys($arrayAttributes)
    {
        foreach($this->hidden as $attributeKey)
        {
            unset($arrayAttributes[$attributeKey]);
        }

        return $arrayAttributes;
    }

    protected function addAppendKeys()
    {
        $arrayAppends = [];

        foreach($this->appends as $attributeKey)
        {
            $arrayAppends[$attributeKey] = null;
        }

        return $arrayAppends;

    }

    protected function applyAppends($arrayAttributes)
    {   
        $arrayAppends = $this->addAppendKeys();

        foreach($arrayAppends as $attributeKey => $attribute)
        {
            $method = 'get' . $attributeKey;

            $arrayAppends[$attributeKey] = $this->{$method}();

        }

        return array_merge($arrayAttributes, $arrayAppends);

    }

    public function toArray()
    { 

        $arrayAttributes = $this->removeHiddenKeys($this->attributes);

        foreach($arrayAttributes as $attributeKey => $attribute)
        {

            $arrayAttributes[$attributeKey] = $this->$attributeKey;
            
        }

        return $this->applyAppends($arrayAttributes);
    
    }

    public function databaseOriginal()
    {
        //fakeam povlačenje iz baze i postavljam $orignal
        $this->original = $this->attributes;

    }

    protected function isDirty($attributeKey)
    {
        return $this->attributes[$attributeKey] !== $this->original[$attributeKey];
    }

    protected function dirtyFields($attributes)
    {
        $dirtyFields = [];

        foreach($attributes as $attributeKey => $attribute)
        {
            if($this->isDirty($attributeKey))
            {
                $dirtyFields[$attributeKey] = $attribute;
            }
        }

        return $dirtyFields;

    }

    public function update(Array $updated)
    {

        foreach($updated as $updateKey => $update)
        {   
            $dirtyFields = [];

            if($this->attirbutes[$updateKey] !== $updated[$updateKey])
            {   
                $this->attributes[$updateKey] = $updated[$updateKey];
            }

        }

        $dirtyFields = $this->dirtyFields($this->attributes);

        die(var_dump($dirtyFields));

    }


}