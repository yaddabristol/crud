<?php namespace Yaddabristol\Crud\Interfaces;

/**
 * @todo    Document this!
 * @author  Andrew Ellender <andrew@yadda.co.uk>
 * @license MIT
 */
interface Searchable
{

  function getSearchables();
  function scopeSimpleSearch($query, $search_term, $loose = true);

}
