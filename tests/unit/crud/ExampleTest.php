<?php

use Yaddabristol\Crud\Exceptions\InvalidCrudInitialisationException;

use Yaddabristol\Crud\Classes\CrudManager;

class ExampleTest extends PHPUnit_Framework_TestCase
{
    protected $crud;

    public function setUp() {
        $this->crud = (new CrudManager)->initialize([
            'views_dir'     => 'things',
            'name_singular' => 'Thing',
            'name_plural'   => 'Things',
            'body_classes'  => [],
            'route'         => 'admin.things',
            'table_columns' => ['id', 'title'],
            'paginate'      => false,
            'form_fields'   => [
                'Main' => [ 
                    'title' => [
                        'type' => 'text',
                        'label' => 'Title',
                        'placeholder' => 'The name of the thing',
                        'required' => true
                    ],
                    'slug' => [
                        'type' => 'slug',
                        'label' => 'Title',
                        'placeholder' => 'The internal name of the thing',
                        'source' => '',
                        'required' => true
                    ],
                    'content' => [
                        'type' => 'wysiwyg',
                        'label' => 'Content',
                        'placeholder' => 'The things about the thing',
                        'required' => false
                    ]
                ],
                'SEO' => [
                    'meta_title' => [
                        'type' => 'text',
                        'label' => 'Meta Title',
                        'placeholder' => 'The Meta name of the thing',
                        'required' => false
                    ]
                ],          
            ]
        ]);
    }

    /**
     * @test: A basic functional test example.
     * 
     * @return void
     */
    public function testsCrudManagerInitilisation()
    {
        $this->assertEquals($this->crud->getProperty('views_dir'), 'things');
        $this->assertEquals($this->crud->getProperty('name_singular'), 'Thing');
        $this->assertEquals($this->crud->getProperty('name_plural'), 'Things');
        $this->assertEquals($this->crud->getProperty('route'), 'admin.things');
        $this->assertEquals($this->crud->getProperty('table_columns'), ['id', 'title']);
        $this->assertEquals($this->crud->getProperty('paginate'), false);
        $this->assertEquals($this->crud->getProperty('body_classes'), []);
        $this->assertEquals($this->crud->tabsCount(), 2);

        $this->setExpectedException(InvalidCrudInitialisationException::class);
        $this->crud->initialize(['something-not-allowed' => 'value']);
        $this->crud->initialize('something-not-an-array-or-CrudController');
    }

    /**
     * @test: Tests addind body classes
     */
    public function canAddBodyClasses()
    {
        $body_class = 'test-class';
        $body_classes = ['test-class-two', 'test-class-three'];

        // Add single class
        $this->crud->addBodyClasses($body_class);
        $this->assertEquals($this->crud->getProperty('body_classes'), ['test-class']);

        // Add multpile classes
        $this->crud->addBodyClasses($body_classes);
        $this->assertEquals($this->crud->getProperty('body_classes'), ['test-class', 'test-class-two', 'test-class-three']);

        // Test that adding a duplicate doesn't actualy add it.
        $this->crud->addBodyClasses($body_class);
        $this->assertEquals(count($this->crud->getProperty('body_classes')), 3);

        // Test the string getter
        $this->assertEquals($this->crud->getBodyClassString(), "test-class test-class-two test-class-three");

        // Test Adding a non-string item
        $this->setExpectedException(InvalidCrudInitialisationException::class);
        $this->crud->addBodyClasses(new stdClass);
    }

    /**
     * @test: Test removing body classes
     */
    public function removeBodyClasses()
    {
        $body_class = 'test-class';
        $body_classes = ['test-class-two', 'test-class-three'];
        $this->crud->addBodyClasses(array_merge([$body_class], $body_classes));

        // Remove single class. Have to set index for it to count as a match
        // (because the match cares about array indexes, while body classes do not).
        $this->crud->removeBodyClasses($body_class);
        $this->assertEquals($this->crud->getProperty('body_classes'), [1 => 'test-class-two', 2 => 'test-class-three']);

        // Remove class that doesn't exist
        $this->crud->removeBodyClasses($body_class);
        $this->assertEquals($this->crud->getProperty('body_classes'), [1 => 'test-class-two', 2 => 'test-class-three']);       

        // Remove multiple classees.
        $this->crud->removeBodyClasses($body_classes);
        $this->assertEquals($this->crud->getProperty('body_classes'), []);

        // Test Adding a non-string item
        $this->setExpectedException(InvalidCrudInitialisationException::class);
        $this->crud->removeBodyClasses(new stdClass);
    }
}
