<?php namespace Yaddabristol\Crud\Classes;

use Yaddabristol\Crud\Exceptions\InvalidCrudInitialisationException;
use Yaddabristol\Crud\Controllers\CrudController;

/**
 * @todo    Document this!
 * @author  Andrew Ellender <andrew@yadda.co.uk>
 * @license MIT
 */
class CrudManager {

    /**
     * Directory that views are stored in. E.g. 'admin.model'
     *
     * @var string
     */
    protected $views_dir;

    /**
     * Name of the model handled by this controller.
     *
     * @var string
     */
    protected $name_singular;

    /**
     * Plural name of the model handled by this controller.
     *
     * @var string
     */
    protected $name_plural;

    /**
     * Base route for this controller.
     *
     * @var string
     */
    protected $route;

    /**
     * Classes to be added to the body tag
     *
     * @var array
     */
    protected $body_classes = [];

    /**
     * Index page table column headings and associated attributes
     *
     * @var array
     */
    protected $table_columns = [];

    /**
     * Fields to use in autogenerated form
     *
     * The options specified here only affect the HTML that is generated by
     * autoform.blade.php. They do not otherwise affect the application.
     * Because of this you can easily create your own field types by just
     * creating a blade file. e.g. views/fields/YOURFIELDNAME.blade.php
     *
     * @var array
     */
    protected $form_fields = [];

    /**
     * @todo What's this for?
     * @var  array
     */
    protected $allowed_attributes = [
        'views_dir',
        'name_singular',
        'name_plural',
        'route',
        'body_classes',
        'table_columns',
        'paginate',
        'form_fields'
    ];

    /**
     * Applies a set of data to this object
     *
     * @param  Mixed        $attributes  Array or instance of CrudController
     * @return CrudManager               Modified $this
     */
    public function initialize($attributes)
    {
        // if $attributes is an instance of crud controller pull properties,
        // otherwise ensure attributes is an array
        if(is_object($attributes) && $attributes instanceof CrudController)
            $attributes = $attributes->getCrudAttributes();
        elseif(!is_array($attributes))
            throw new InvalidCrudInitialisationException("Data passed in incorrect format");

        foreach($attributes as $attribute_name => $attribute_value) {
            if(!in_array($attribute_name, $this->allowed_attributes))
                throw new InvalidCrudInitialisationException("Attempted to set unrecognised value: {$attribute_name}");

            $this->$attribute_name = $attribute_value;
        }

        return $this;
    }

    /**
     * Getter function for properties
     *
     * @param  string  $property_name  name of property to get
     * @return mixed                   property value or null
     */
    public function getProperty($property_name)
    {
        return (property_exists($this, $property_name) ? $this->$property_name : null);
    }

    /**
     * Returns all the body classes as a space separated string
     *
     * @return string
     */
    public function getBodyClassString()
    {
        return implode(' ', $this->body_classes);
    }

    /**
     * Adds an array of classes to the body classes array, removing duplicates
     * @param  mixed  $classes  classes to add
     */
    public function addBodyClasses($classes)
    {
        if(is_array($classes)) {
            foreach($classes as $class) {
                $this->addBodyClass($class);
            }
        } else {
            $this->addBodyClass($classes);
        }
    }

    /**
     * Adds a single class to the body classes array, if it isn't already present
     * @param  string  $class  class to add
     */
    protected function addBodyClass($class)
    {
        if(!stringTest($class))
            throw new InvalidCrudInitialisationException("Classes passed to addBodyClasses must be strings");

        if(!in_array($class, $this->body_classes))
            $this->body_classes[] = (string) $class;
    }

    /**
     * Delegates removes a/some body class(es) from the current array, if they exist,
     * to the protected method
     *
     * @param  mixed  $classes  array of classes or string of single class
     *                          to remove
     */
    public function removeBodyClasses($classes)
    {
        if(is_array($classes)) {
            foreach($classes as $class) {
                $this->removeBodyClass($class);
            }
        } else {
            $this->removeBodyClass($classes);
        }
    }

    /**
     * Function that does the actual removeing of a class from the array.
     *
     * @param  string  $class  class to remove
     */
    protected function removeBodyClass($class)
    {
        if(!stringTest($class))
            throw new InvalidCrudInitialisationException("Classes passed to addBodyClasses must bt strings");

        if(false !== $key = array_search($class, $this->body_classes))
            unset($this->body_classes[$key]);
    }

    /**
     * Returns a count of the tabs this will currently create
     *
     * @return integer          tab count
     */
    public function tabsCount()
    {
        return count($this->form_fields);
    }

    /**
     * Adds an array of fields to the body fields array, removing duplicates
     * @param  string   $tab_name   tab to add
     * @param  array    $fields     fields to add
     * @param  boolean  $overwrite  whether to overwrite existing data if duplicate
     *                              field name given
     */
    public function addFormFields($tab_name, $fields, $overwrite = true)
    {
        if(!is_array($fields) )
            throw new InvalidCrudInitialisationException("Fields passed to addFormFields must be an array of field data arrays");

        foreach($fields as $field_name => $field_data) {
            $this->addFormField($tab_name, $field_name, $field_data, $overwrite);
        }
    }

    /**
     * Adds a single field to the body fields array, if it isn't already present
     * @param  string   $tab_name    tab name
     * @param  string   $field_name  field name
     * @param  array    $field_data  field data to add to array
     * @param  boolean  $overwrite   whether to overwrite existing items if
     *                                duplicate is given
     */
    public function addFormField($tab_name, $field_name, $field_data, $overwrite = true)
    {
        if(!is_array($field_data))
            throw new InvalidCrudInitialisationException("Fields passed to addFormFields must be arrays of field data");

        if(!isset($this->form_fields[$tab_name]) || !array_key_exists($field_name, $this->form_fields[$tab_name]) || $overwrite)
            $this->form_fields[$tab_name][$field_name] = $field_data;
    }

    /**
     * Defers the removal of a tab -> field combination to a single function, for
     * running through arrays of fields at once
     *
     * @param  string  $tab_name     Name of tab to remove from
     * @param  mixed   $field_names  Name of field, or array of names
     */
    public function removeFormFields($tab_name, $field_names)
    {
        if(is_array($field_names)){
            foreach($field_names as $field_name) {
                $this->removeFormField($tab_name, $field_name);
            }
        } else {
            $this->removeFormField($tab_name, $field_names);
        }
    }

    /**
     * Removes a given field from a given tab in the stored form_fields array
     *
     * @param  string  $tab_name    Name of tab to remove from
     * @param  string  $field_name  Name of field to remove
     */
    public function removeFormField($tab_name, $field_name)
    {
        if(!stringTest($field_name))
            throw new InvalidCrudInitialisationException;

        if(isset($this->form_fields[$tab_name]) && isset($this->form_fields[$tab_name][$field_name])) {
            unset($this->form_fields[$tab_name][$field_name]);
            $this->unsetTabIfEmpty($tab_name);
        }
    }

    /**
     * Checks whether a given tab name has any fields present, and unsets it if empty
     *
     * @param  string  $tab_name  Name of tab to check
     */
    protected function unsetTabIfEmpty($tab_name)
    {
        if(isset($this->form_fields[$tab_name]) && empty($this->form_fields[$tab_name]))
            unset($this->form_fields[$tab_name]);
    }
}
