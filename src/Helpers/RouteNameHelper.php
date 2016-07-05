<?php namespace Yaddabristol\Crud\Helpers;

class RouteNameHelper {

  protected $route_name;
  protected $separator;

  public function __construct($route_name, $separator)
  {
    $this->route_name = $route_name;
    $this->separator = $separator;
  }

  /**
   * Readable function to Split a string into an array, 
   * with a known separator
   * 
   * @return array             sections of original string
   */
  protected function getPartsArray()
  {
    return explode($this->separator, $this->route_name);
  }

  /**
   * Returns an array with all single parts of the stored route_name, 
   * along with array_shifted subsections, e.g. admin-things-create, things-create
   * @return [type] [description]
   */
  public function getAllParts()
  {
    $all_parts = $parts = $this->getPartsArray();

    // $all_parts[0] shouldn't be empty, although potentially could be if provided
    // with the same route_name as separator (in the case of route_name '/')
    if(empty($all_parts[0])) return ['home'];
  
    // Loop through, adding consecutively smaller partial names 
    for($i = 0; $i < count($parts); $i++) {
      $all_parts[] = implode('-', $parts);
      array_shift($parts);
    }

    return $all_parts;
  }
}