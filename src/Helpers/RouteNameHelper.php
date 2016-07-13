<?php namespace Yaddabristol\Crud\Helpers;

/**
 * Split a route into helpful chunks
 *
 * Converts route names into an array of its constituent parts, separated by a
 * given separator. If the first element is empty, return an array with just
 * 'home' in, as it should only happen when calling '/' route path.
 *
 * @author  Andrew Ellender <andrew@yadda.co.uk>
 * @license MIT
 */
class RouteNameHelper {

  protected $route_name;
  protected $separator;
  protected $route_prefix;

  public function __construct($route_name, $separator, $route_prefix = 'route-')
  {
    $this->route_name   = $route_name;
    $this->separator    = $separator;
    $this->route_prefix = $route_prefix;
  }

  /**
   * Readable function to Split a string into an array, with a known separator
   *
   * @return array  sections of original string
   */
  protected function getPartsArray()
  {
    return explode($this->separator, $this->route_name);
  }

  /**
   * Get all parts of the route
   *
   * Returns an array with all single parts of the stored route_name, along
   * with array_shifted subsections, e.g. admin-things-create, things-create
   *
   * @return array  sections and partials from original string
   */
  public function getAllParts()
  {
    $all_parts = $parts = $this->getPartsArray();

    // $all_parts[0] shouldn't be empty, although potentially could be if provided
    // with the same route_name as separator (in the case of route_name '/')
    if (empty($all_parts[0])) {
      return ['home'];
    }

    // Loop through, adding consecutively smaller partial names
    for ($i = 0; $i < count($parts); $i++) {
        $all_parts[] = implode('-', $parts);
        array_shift($parts);
    }

    return $this->addRoutePrefixToClasses($all_parts);
  }

  /**
   * Adds a given route prefix to all classes passed in
   *
   * @param  array  $classes  array of class strings
   * @return array  $classes  modified array
   */
  public function addRoutePrefixToClasses($classes)
  {
    foreach ($classes as $index => $class) {
      $classes[$index] = $this->route_prefix . $class;
    }

    return $classes;
  }
}
