# CRUD

Extendable CRUD controller for quick development.


## Requirements

* Laravel 5.1+
* Composer


## Installation

* `composer require yadda/crud`
* Add `Yadda\Crud\CrudServiceProvider::class` to the `providers` section in `config/app.php`
* Publish package config

        php artisan vendor:publish

* Update `config/crud.php` as needed
* Make a controller that extends `YaddaBristol\Crud\Controllers\CrudController`

        <?php

        namespace App\Html\Controllers;

        use YaddaBristol\Crud\Controllers\CrudController;

        class ThingController extends CrudController
        {
            ...
        }

* Make a model and corresponding database table

        php artisan make:model Thing
        php artisan make:migration create_thing_table --create=things

* Override some properties on your controller (see below)
* Override methods on the controller as needed
* Maybe define some fields for automatic form creation (see below)
* Add LaravelCollective's Html provider to `config/app.php`:

        Collective\Html\HtmlServiceProvider::class,

* Also add the aliases to `config/app.php`

        'Form' => Collective\Html\FormFacade::class,
        'Html' => Collective\Html\HtmlFacade::class,

* Make a website

## Configuration

You'll want to override some of these things in your controller. See the source code of `Controllers\CrudController` for more info.

    protected $model = Thing::class;
    protected $views_dir = 'thing_views';
    protected $name_singular = 'Thing';
    protected $name_plural = 'Things';
    protected $route = 'admin';
    protected $rules = [];
    protected $messages = [];
    protected $paginate = false;
    protected $settings = [
      'perpage' => 20,
      'orderby' => 'id',
      'order' => 'ASC'
    ];
    protected $searchables = ['id'];
    protected $group_by = 'id';
    protected $has_files = false;
    protected $form_fields = [];
    protected $table_columns = ['id'];


## Form Generation

To automatically generate a form add something like the following to your form.blade.php

    @include('crud::partials.autoform', [
      'fields' => $form_fields,
      'model'  => $item
    ])

Then populate the `form_fields` attribute of your controller with an array of fields. E.g.

    protected $form_fields = [
        'your_field_name' => [
            'type' => 'text',
            'label' => 'A Text Field',
            'placeholder' => 'Put some text here',
            'help_text' => 'This is a field for entering text',
            'required' => true
        ]
    ];

### Available Options

* **type** - The type of input to use. Options: `checkbox`, `file`, `image`, `radio`, `slug`, `text`, `textarea`, `wysiwyg`. Default: `'text'`.
* **label** - The text label for your field. Default: the name with first letters capitalised.
* **placeholder** - The placeholder text. Default: `''`.
* **help_text** - Extra help text. Default: `''`.
* **required** - Make the field required. Defaults to `false`.
* **source** - Slug fields only. Required. The name of the field to auto-populate content from.
* **choices** - Radio/select fields only. An array of available choices. Array index represents input value, array key represents input label.


## Usage Notes

...


## Gotchas


### File uploads

If you add a file upload to a form, you'll need to set `protected $has_files = true;` on your controller.


## Todo

* Split out search stuff into a separate trait
