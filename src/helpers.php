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
          return app('crud.manager')->getProperty($attribute_name);
    }
}

if (! function_exists('stringTest')) {
    /**
     * Tests whether a given value is, or could be converted into, a string
     *
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

if (! function_exists('getActionName')) {
    /**
     * Get the action name for the curren route
     *
     * @return  string  Name of current action, e.g. edit
     */
    function getActionName()
    {
        $action_name = request()->route()->getActionName();
        list($x, $name) = explode('@', Route::getCurrentRoute()->getActionName());
        return $name;
    }
}

/**
 * Recursive glob()
 *
 * @link        http://php.net/glob
 * @author      HM2K <hm2k@php.net>
 * @version     $Revision: 1.2 $
 * @require     PHP 4.3.0 (glob)
 */

/**
 * @param int $pattern
 *  the pattern passed to glob()
 * @param int $flags
 *  the flags passed to glob()
 * @param string $path
 *  the path to scan
 * @return mixed
 *  an array of files in the given path matching the pattern.
 */

if (! function_exists('rglob')) {
    function rglob($pattern='*', $flags = 0, $path=false)
    {
      	if (!$path) { $path=dirname($pattern).DIRECTORY_SEPARATOR; }
      	$pattern=basename($pattern);
      	$paths=glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
      	$files=glob($path.$pattern, $flags);
      	foreach ($paths as $path) {
      		  $files=array_merge($files,rglob($pattern, $flags, $path));
      	}
      	return $files;
    }
}
