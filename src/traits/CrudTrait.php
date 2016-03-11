<?php

namespace Yadda;

use Illuminate\Http\Request as Request;

trait CrudControllerTrait
{
    /**
     * The model handled by this controller.
     * E.g. Model::class or 'App\Models\MyModel'
     * @var string
     */
    protected $model = null;

    /**
     * The directory that views are stored in. E.g. 'admin.model'
     * @var string
     */
    protected $views_dir = '';

    /**
     * The name of the model handled by this controller.
     * @var string
     */
    protected $name_singular = 'Base Model';

    /**
     * The plural name of the model handled by this controller.
     * @var string
     */
    protected $name_plural = 'Base Models';

    /**
     * The base route for this controller.
     * @var string
     */
    protected $route = 'admin.base';

    /**
     * Basic validation rules. You may want to alter this in custom
     * doUpdate or doStore methods if they aren't the same.
     */
    protected $rules = [];

    /**
     * Custom validation error messages
     */
    protected $messages = [];

    /**
     * Set this to true to enable pagination on index pages.
     */
    protected $paginate = false;

    /**
     * Settings for ordering, searching and pagination
     */
    protected $settings = [
        'perpage' => 20,
        'orderby' => 'id',
        'order' => 'ASC'
    ];

    /**
     * Fields to search by
     */
    protected $searchables = ['id'];

    /**
     * unique ID column to group by (for advanced queries, may require setting
     * to tablename.column)
     */
    protected $group_by = 'id';

    /**
     * Url mods for building pagination appendations
     */
    protected $url_mods;

    /**
     * Classes to be added to the body tag
     */
    protected $body_classes = [];

    public function __construct(Request $request)
    {
        $this->validate($request, [
            'perpage' => 'numeric|filled',
            'orderby' => 'string|filled|required_with:order',
            'order' => 'in:ASC,DESC|filled|required_with:orderby',
            'search' => 'string'
        ]);

        if($request->has('perpage')) $this->settings['perpage'] = $request->get('perpage');
        if($request->has('orderby')) $this->settings['orderby'] = $request->get('orderby');
        if($request->has('order')) $this->settings['order'] = $request->get('order');
        if($request->has('search')) $this->settings['search'] = $request->get('search');

        $this->generateUrlMods();
        view()->share(['url_mods' => $this->url_mods, 'settings' => $this->settings]);

        // Append current endpoint name to list of classes
        if(Route::getCurrentRoute()) {
            $route = explode('.', Route::getCurrentRoute()->getName());
            array_shift($route);
            $this->body_classes = $this->body_classes + $route;
            $this->body_classes[] = implode('-', $route);
        }
        view()->share('views_dir', $this->views_dir);
        view()->share('name_singular', $this->name_singular);
        view()->share('name_plural', $this->name_plural);
        view()->share('body_classes', $this->body_classes);
        view()->share('route', $this->route);

        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = call_user_func($this->model.'::latest');

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

            return view($this->views_dir . '.index', compact('items'));
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->body_classes[] = 'create';
        $item = new $this->model;

        return view($this->views_dir . '.create', compact('item'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules, $this->messages);

        call_user_func($this->model . '::create', request()->all());

        if ($request->ajax()) {
            return json_encode([
                'status' => 'success',
                'data' => null
            ]);
        } else {
            return redirect()->route($this->route . '.index')->with('success', $this->name_singular . ' was created successfully.');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $this->body_classes[] = 'show';
        $item = call_user_func($this->model . '::findOrFail', $id);

        if ($request->ajax()) {
            return json_encode($item);
        } else {
            return view($this->views_dir . '.show', compact('item'));
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->body_classes[] = 'edit';
        $item = call_user_func($this->model . '::findOrFail', $id);

        return view($this->views_dir . '.edit', compact('item'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules, $this->messages);

        $item = call_user_func($this->model . '::findOrFail', $id);
        $item->update($request->all());

        if ($request->ajax()) {
            return json_encode([
                'status' => 'success',
                'data' => null
            ]);
        } else {
            return redirect()->route($this->route . '.index');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = call_user_func($this->model . '::findOrFail', $id);
        $item->delete();

        return redirect()->route($this->route . '.index')->with('success', $this->name_singular . ' has been deleted.');
    }

    protected function generateUrlMods()
    {
        $mods = [
            'perpage' => "perpage={$this->settings['perpage']}",
            'orderby' => "orderby={$this->settings['orderby']}",
            'order' => "order={$this->settings['order']}",
        ];

        if(!empty($this->settings['search'])) {
            $mods['search'] =  "search={$this->settings['search']}";
        }

        $this->url_mods = $mods;

        return;
    }

    protected function doSearch($query)
    {
        if(!empty($this->settings['search']) && !empty($this->searchables)) {
            $query->where(function($query) {
                $first = array_shift($this->searchables);
                $query->where("{$first}", 'like', "%{$this->settings['search']}%");
                foreach($this->searchables as $column_name) {
                    $query->orWhere("{$column_name}", 'like', "%{$this->settings['search']}%");
                }
            });
        }
        return $query;
    }

    protected function finishQuery($query)
    {
      $query->orderBy($this->settings['orderby'], $this->settings['order']);
      $query = $this->doSearch($query);
      $query->groupBy($this->group_by);

      if($this->paginate) return $query->paginate($this->settings['perpage']);
      else return $query->get();
    }
    /**
     * Add a class to be attached to the body tag
     * @param string $class
     */
    public function addBodyClass($class)
    {
        $this->body_classes[] = $class;
        view()->share('body_classes', $this->body_classes);
    }
}
