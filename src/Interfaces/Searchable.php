<?php namespace Yaddabristol\Crud\Interfaces;

interface Searchable 
{

  function getSearchables();
  function scopeSimpleSearch($query, $search_term, $loose = true);

}