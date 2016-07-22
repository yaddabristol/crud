# CRUD

Extendable CRUD controller for quick development.


## Requirements

* Laravel 5.1+
* Composer


## Installation

* `composer require yadda/crud`
* Add the following to to the `providers` section in `config/app.php`

        Yaddabristol\Crud\CrudServiceProvider::class,
        Collective\Html\HtmlServiceProvider::class,

* Add the aliases to `config/app.php`

        'Form' => Collective\Html\FormFacade::class,
        'Html' => Collective\Html\HtmlFacade::class,

* Publish package config

        php artisan vendor:publish

* Update `config/crud.php` as needed
* Make a controller that extends `YaddaBristol\Crud\Controllers\CrudController`

        <?php

        namespace App\Html\Controllers;

        use Yaddabristol\Crud\Controllers\CrudController;

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
* **rows** - Textarea fields only. Number of rows height the input area is.


## Usage Notes

### Images

The `image` field type assumes the use of [Laravel Stapler](https://github.com/CodeSleeve/laravel-stapler) for attaching images to models. If you don't want to use that, you can easily override the image field type or create your own. Just copy `crud/src/Views/fields/image.blade.php` to `{YOUR_APP}/resources/views/fields/image.blade.php`.

### Default Values

To add default values in a create form, set `protected $attributes = [];` on your model.

## Gotchas

### Models

Remember to set up the Fillable fields of the Model, otherwise it will fail with a mass assignment
exception on `_token`.

### File uploads

If you add a file upload to a form, you'll need to set `protected $has_files = true;` on your controller.

### Development

Need to add line to base-install under autoload - PSR4 when developing from the packages folder: "Yaddabristol\\Crud\\": "packages/yadda/crud/src/".

You'll need to require the laravelcollective/html package from your base install as you'll
be developing in the packages folder and it won't automatically register it's requirements

## Documentation

To generate the docs use apigen:

    apigen generate -s src -d docs

## Todo

* Split out search stuff into a separate trait
