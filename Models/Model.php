<?php

require "Traits/Observer.php";

class Model {

    use Observer;

    protected $attributes = [];

    protected $original = [];

    protected $appends = []; 

    protected $hidden = [];

    protected $timestamps = true;

    protected $autoIncrement = true;

    protected $primaryKey = 'id';

    protected $tableName = null;

    protected $exists = false;

    public static $query;

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

    protected function isDirty($attributeKey)
    {
        return $this->attributes[$attributeKey] !== $this->original[$attributeKey];
    }

    protected function dirtyFields($attributes)
    {
        $dirtyFields = [];

        unset($attributes['created_at'], $attributes['updated_at']);

        foreach($attributes as $attributeKey => $attribute)
        {
            if($this->isDirty($attributeKey))
            {
                $dirtyFields[$attributeKey] = $attribute;
            }
        }

        return $dirtyFields;

    }

    public function update(Array $parameters = [])
    {
        $dirtyFields = [];

        foreach($parameters as $parameterKey => $parameter)
        {   
            if($this->attirbutes[$parameterKey] !== $parameter)
            {   
                $this->$parameterKey = $parameter;
            }

        }

        $dirtyFields = $this->dirtyFields($this->attributes);

        if(empty($dirtyFields))
        {   
            return;
        }

        $datetime = date('H:i d.m.Y');
        $parameters += ['updated_at' => $datetime];

        static::$query->update($this->tableName, $dirtyFields, [$this->primaryKey => $this->attributes[$this->primaryKey]]);

        $this->original = $this->attributes;

    }

    public function fill(Array $parameters = [])
    {   
        foreach($parameters as $parameterKey => $parameter)
        {   
            $this->$parameterKey = $parameter;
        }

    }

    public function save()
    {   
        if($this->exists)
        {   
            $this->update($this->attributes);
        }

        if($this->timestamps)
        {
            $datetime = date('H:i d.m.Y');
            $this->attributes += ['created_at' => $datetime, 'updated_at' => $datetime];
        }

        $insertedId = static::$query->insert($this->tableName, $this->attributes);

        $this->triger('created');

        $this->exists = true;

        if($insertedId != "0") {
            $this->attributes[$this->primaryKey] = $insertedId;
        }

    }

}
