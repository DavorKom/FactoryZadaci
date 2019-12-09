<?php 

trait Observer
{
    public static $observers = [];

    public static function observe($observer)
    {   
        $key = spl_object_hash($observer);
        static::$observers[$key] = $observer;
    }

    public function triger($observerMethod)
    {   
        foreach(static::$observers as $observer){
            if(method_exists($observer, $observerMethod)){
                $observer->$observerMethod($this);
            }
        }
    }
}