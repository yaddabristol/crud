<?php namespace Yaddabristol\Crud\Traits;

use Exception;

/**
 * Add to an Eloquent Model to provide extra query scoping
 *
 * @author  Andrew Ellender <andrew@yadda.co.uk>
 * @license MIT
 */
trait Searchable
{

  /**
   * Gets the searchables array from the object that this trait is on,
   * or a default array if it's not been set
   *
   * @return array                  array of columns to search
   */
  protected function getSearchables()
  {
    return property_exists($this, 'searchables') ? $this->searchables : ['id'];
  }

  /**
   * A scope filter to only show items which have a given string in one
   * or more of it's columns, defined by the searchables property
   *
   * @param  Builder  $query        Original query
   * @param  string   $search_term  term to search for
   * @param  boolean  $loose        whether to form exact matches only
   * @return Builder                Filtered query
   */
  public function scopeSimpleSearch($query, $search_term, $loose = true)
  {
    if ($loose) {
      $search_term = "%{$search_term}%";
    }

    return $query->where(function($sub_query) use ($search_term) {

      $searchables = $this->getSearchables();

      if (count($searchables) === 0) {
        throw new Exception("Attempting to search Model with no fields to search by");
      } elseif (count($searchables) === 1) {
        return $sub_query->where($searchables[0], 'LIKE', $search_term);
      } else {
        // Pop the first item to initiate a where set
        $first = array_shift($searchables);
        $sub_query->where($first, 'LIKE', $search_term);

        // Then loop through the remainder to add them as 'or where' participants
        foreach ($searchables as $column_name) {
          $sub_query->orWhere($column_name, 'LIKE', $search_term);
        }
      }

      return $sub_query;
    });
  }
}
