<?php

if (! function_exists('crud')) {
    /**
     * Helper to get the singleton instance of CrudResolver
     * from the IoC container.
     */
    function crud($attribute_name = null)
    {
        if(is_null($attribute_name))
          return app('crud.manager');
        else
          return app('crud.manager')->$attribute_name;
    }
}

if (! function_exists('stringTest')) {
    /**
     * Tests whether a given value is, or could be converted into, a string
     * @param  mixed      $value          Value to check
     * 
     * @return boolean                    Whether it [is/can be] a string
     */
    function stringTest($value)
    {
        try { (string) $value; }
        catch (Exception $e) { return false; }
        return true;
    }
}