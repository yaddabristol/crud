<?php

namespace Yaddabristol\Crud\Controllers;

use Illuminate\Http\Request as Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Route;

use Yaddabristol\Crud\Helpers\RedirectHelper;
use Yaddabristol\Crud\Helpers\RouteNameHelper;
use Yaddabristol\Crud\Classes\CrudManager;
use Yaddabristol\Crud\Interfaces\Searchable as SearchableInterface;

/**
 * Http controller for CRUD
 *
 * @author  Jake Gully <jake@yadda.co.uk>
 * @author  Andrew Ellender <andrew@yadda.co.uk>
 * @license MIT
 */
abstract class CrudController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * The model handled by this controller.
     * E.g. Model::class or 'App\Models\MyModel'
     *
     * @var string
     */
    protected $model = null;

    /**
     * Directory that views are stored in. E.g. 'admin.model'
     *
     * @var string
     */
    protected $views_dir = '';

    /**
     * The name of the model handled by this controller.
     *
     * @var string
     */
    protected $name_singular = 'Base Model';

    /**
     * The plural name of the model handled by this controller.
     *
     * @var string
     */
    protected $name_plural = 'Base Models';

    /**
     * The base route for this controller.
     *
     * @var string
     */
    protected $route = 'admin.base';

    /**
     * String to prefix all classes discovered by the RouteNameHelper with
     *
     * @var string
     */
    protected $route_class_prefix = 'route-';

    /**
     * Basic validation rules. You may want to alter this in custom
     * doUpdate or doStore methods if they aren't the same.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Custom validation error messages
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Set this to true to enable pagination on index pages.
     *
     * @var boolean
     */
    protected $paginate = false;

    /**
     * Settings for ordering, searching and pagination
     *
     * @var array
     */
    protected $settings = [
        'perpage' => 20,
        'orderby' => 'id',
        'order' => 'ASC'
    ];

    /**
     * Setting for adding additional filtering to the index query.
     *
     * @var array
     */
    protected $additional_query_scope_filters = [];

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
    protected $table_columns = [
        'ID' => 'id',
        'Name' => 'name',
        'Slug' => 'slug'
    ];

    /**
     * Set to true to enable file uploading.
     *
     * @var boolean
     */
    protected $has_files = false;

    /**
     * Fields to use in autogenerated form. The options specified here only
     * affect the HTML that is generated by autoform.blade.php. They do not
     * otherwise affect the application. Because of this you can easily
     * create your own field types by just creating a blade file.
     * e.g. views/fields/YOURFIELDNAME.blade.php
     *
     * @var array
     */
    protected $form_fields = [];

    /**
     * Separator between entities in the route name. Laravel defaults to
     * '.' and should mostly be left as such, but can be overridden.
     *
     * @var string
     */
    protected $route_name_separator = '.';

    /**
     * Additional data to append to or override on the request. I.e. for
     * adding functionality in beforeStore(), afterStore() etc.
     *
     * @var array
     */
    protected $data = [];

    /**
     * The item currently being created/updated. For use in beforeStore,
     * afterStore, beforeUpdate, afterUpdate.
     *
     * @var Illuminate\Database\Eloquent\Model
     */
    protected $item = null;

    /**
     * Routes to redirect to after the given actions
     *
     * @var array
     */
    protected $redirect_routes = [
        'store'   => 'index',
        'update'  => 'index',
        'destroy' => 'index',
    ];

    /**
     * Class to use to work out dynamically redirect after
     * successful form submissions
     *
     * @var string
     */
    protected $redirect_helper_class = RedirectHelper::class;

    public function __construct(Request $request)
    {
        $this->setupRedirectRoutes();

        $this->request = $request;
        $this->redirector = new $this->redirect_helper_class($this);

        $this->addRouteToBodyClasses();

        // Initialise the IoC container's instance of CrudManager with
        // the settings from this controller
        crud()->initialize($this);
    }

    /**
     * Adds required parts of the current route's name to $this->body_classes.
     * Or does nothing if this isn't a names route.
     *
     * @return void
     */
    protected function addRouteToBodyClasses()
    {
        $current_route = $this->request->route();
        $separator = $this->route_name_separator;

        // Splits by name if the route has one, by path if not
        if(!empty($current_route)) {
            $route_name = $current_route->getName();
            if(is_null($route_name)) {
                $separator = '/';
                $route_name = $current_route->getPath();
            }

            $all_parts = (new RouteNameHelper($route_name, $separator, $this->route_class_prefix))->getAllParts();
            $this->body_classes = array_merge($this->body_classes, $all_parts);
        }
    }

    /**
     * Returns an array of crud-related attributes on this object
     *
     * @api
     * @return array Name=>value pairs
     */
    public function getCrudAttributes()
    {
        return [
            'views_dir'     => $this->views_dir,
            'name_singular' => $this->name_singular,
            'name_plural'   => $this->name_plural,
            'body_classes'  => $this->body_classes,
            'route'         => $this->route,
            'table_columns' => $this->table_columns,
            'paginate'      => $this->paginate,
            'form_fields'   => $this->form_fields,
        ];
    }

    /**
     * Display a listing of the resource
     *
     * @api
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = call_user_func($this->model.'::query');
        $items->orderBy($this->settings['orderby'], $this->settings['order']);

        if(in_array('Yaddabristol\Crud\Interfaces\Searchable', class_implements($this->model)) &&
            $this->request->has('search')) {
            $items->simpleSearch($this->request->get('search'));
        }

        if ($request->ajax()) {
            $output = [];

            if (!empty($request->all())) {
                $items = call_user_func($this->model.'::where', $request->all())->get();
            }

            foreach ($items as $item) {
                $output[] = $item->attributesToArray();
            }

            return json_encode($output);
        } else {
            if ($this->paginate) {
                $items = $items->paginate($this->settings['perpage']);
            } else {
                $items = $items->get();
            }

            if (view()->exists($this->views_dir . '.index')) {
                return view($this->views_dir . '.index', compact('items'));
            } else {
                return view('crud::index', compact('items'));
            }
        }
    }

    /**
     * Show the form for creating a new resource
     *
     * @api
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new $this->model;
        $data = [
            'item' => $item,
            'has_files' => $this->has_files,
        ];

        $this->beforeCreate();

        $data = array_merge($data, $this->data);

        if (view()->exists($this->views_dir . '.create')) {
            return view($this->views_dir . '.create', $data);
        } else {
            return view('crud::create', $data);
        }
    }

    /**
     * Hook for before the item create form is displayed
     *
     * @api
     * @return void
     */
    protected function beforeCreate() {}

    /**
     * Save the details from the create form to the database
     *
     * @api
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->beforeStore();

        $this->validate($request, $this->rules, $this->messages);
        $data = array_merge(request()->all(), $this->data);
        $this->item = call_user_func($this->model . '::create', $data);

        $this->afterStore();

        if ($request->ajax()) {
            return json_encode([
                'status' => 'success',
                'data' => null
            ]);
        } else {
            return $this->redirector
                ->getRedirect('store')
                ->with('success', $this->name_singular . ' was created successfully.');
        }
    }

    /**
     * Hook for before data is validated and stored
     *
     * @api
     * @return Void
     */
    protected function beforeStore() {}

    /**
     * Hook for after data has been stored
     *
     * @api
     * @return Void
     */
    protected function afterStore() {}

    /**
     * Display the specified resource
     *
     * @api
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $item = call_user_func($this->model . '::findOrFail', $id);

        $this->beforeShow();

        $data = compact('item');

        if ($request->ajax()) {
            return json_encode($item);
        } else {
            if (view()->exists($this->views_dir . '.show')) {
                return view($this->views_dir . '.show', $data);
            } else {
                return view('crud::show', $data);
            }
        }
    }

    /**
     * Hook for before an item page is displayed
     *
     * @api
     * @return void
     */
    protected function beforeShow() {}

    public function edit($id)
    {
        $this->item = call_user_func($this->model . '::findOrFail', $id);

        $data = [
            'item' => $this->item,
            'has_files' => $this->has_files
        ];

        $this->beforeEdit();

        $data = array_merge($data, $this->data);

        if (view()->exists($this->views_dir . '.edit')) {
            return view($this->views_dir . '.edit', $data);
        } else {
            return view('crud::edit', $data);
        }
    }

    /**
     * Hook for before the edit page is displayed
     *
     * @api
     * @return void
     */
    protected function beforeEdit() {}

    /**
     * Update the specified resource in storage
     *
     * @api
     * @param  \App\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->item = call_user_func($this->model . '::findOrFail', $id);
        $this->beforeUpdate();
        $this->validate($request, $this->rules, $this->messages);
        $data = array_merge(request()->all(), $this->data);
        $this->item->update($data);
        $this->afterUpdate();

        if ($request->ajax()) {
            return json_encode([
                'status' => 'success',
                'data' => null
            ]);
        } else {
            return $this->redirector
                ->getRedirect('store')
                ->with('success', $this->name_singular . ' was updated successfully.');
        }
    }

    /**
     * Hook for before data is validated and saved
     *
     * @api
     * @return None
     */
    protected function beforeUpdate() {}

    /**
     * Hook for after data has been saved
     *
     * @api
     * @return None
     */
    protected function afterUpdate() {}

    /**
     * Remove the specified resource from storage
     *
     * @api
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->item = call_user_func($this->model . '::findOrFail', $id);
        $this->beforeDestroy();
        $this->item->delete();

        return $this->redirector
            ->getRedirect('destroy')
            ->with('success', $this->name_singular . ' has been deleted.');
    }

    /**
     * Hook for before item is destroyed
     *
     * @api
     * @return void
     */
    protected function beforeDestroy() {}

    /**
     * Get base route for this controller
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Get request
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get the redirect routes array
     * @return array
     */
    public function getRedirectRoutes()
    {
        return $this->redirect_routes;
    }

    /**
     * Prepends the routes in redirect_routes with the base route. You might
     * want to override this function in your own controller.
     *
     * @return void
     */
    protected function setupRedirectRoutes()
    {
        $this->redirect_routes = [
            'store'   => $this->route . $this->route_name_separator . $this->redirect_routes['store'],
            'update'  => $this->route . $this->route_name_separator . $this->redirect_routes['update'],
            'destroy' => $this->route . $this->route_name_separator . $this->redirect_routes['destroy'],
        ];
    }

}
