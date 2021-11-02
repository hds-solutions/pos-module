<?php

namespace HDSSolutions\Laravel\Http\Controllers;

use App\Http\Controllers\Controller;
use HDSSolutions\Laravel\DataTables\POSDataTable as DataTable;
use HDSSolutions\Laravel\Http\Request;
use HDSSolutions\Laravel\Models\POS as Resource;
use HDSSolutions\Laravel\Models\Customer;
use HDSSolutions\Laravel\Models\Employee;
use HDSSolutions\Laravel\Models\Stamping;

class POSController extends Controller {

    public function __construct() {
        // check resource Policy
        $this->authorizeResource(Resource::class, 'resource');
    }

    public function index(Request $request, DataTable $dataTable) {
        // check only-form flag
        if ($request->has('only-form'))
            // redirect to popup callback
            return view('backend::components.popup-callback', [ 'resource' => new Resource ]);

        // load resources
        if ($request->ajax()) return $dataTable->ajax();

        // return view with dataTable
        return $dataTable->render('pos::pos.index', [
            'count'                 => Resource::count(),
            'show_company_selector' => !backend()->companyScoped(),
        ]);
    }

    public function create(Request $request) {
        // force company selection
        if (!backend()->companyScoped()) return view('backend::layouts.master', [ 'force_company_selector' => true ]);

        // load stampings
        $stampings = Stamping::all();
        // load customers
        $customers = Customer::all();
        // load employees
        $employees = Employee::all();

        // show create form
        return view('pos::pos.create', compact('stampings', 'customers', 'employees'));
    }

    public function store(Request $request) {
        // create resource
        $resource = Resource::create( $request->input() );

        // save resource
        if (count($resource->errors()) > 0)
            // redirect with errors
            return back()->withInput()
                ->withErrors( $resource->errors() );

        // sync resource employees
        if ($request->has('employees')) $resource->employees()->sync(
            // get employees as collection
            $employees = collect($request->get('employees'))
                // filter empty employees
                ->filter(fn($employee) => $employee !== null)
            );

        // check return type
        return $request->has('only-form') ?
            // redirect to popup callback
            view('backend::components.popup-callback', compact('resource')) :
            // redirect to resources list
            redirect()->route('backend.pos');
    }

    public function show(Request $request, Resource $resource) {
        // redirect to list
        return redirect()->route('backend.pos');
    }

    public function edit(Request $request, Resource $resource) {
        // load stampings
        $stampings = Stamping::all();
        // load customers
        $customers = Customer::all();
        // load employees
        $employees = Employee::all();

        // show edit form
        return view('pos::pos.edit', compact('resource',
            'stampings',
            'customers',
            'employees',
        ));
    }

    public function update(Request $request, Resource $resource) {
        // update resource
        if (!$resource->update( $request->input() ))
            // redirect with errors
            return back()->withInput()
                ->withErrors( $resource->errors() );

        // sync resource employees
        if ($request->has('employees')) $resource->employees()->sync(
            // get employees as collection
            $employees = collect($request->get('employees'))
                // filter empty employees
                ->filter(fn($employee) => $employee !== null)
            );

        // redirect to list
        return redirect()->route('backend.pos');
    }

    public function destroy(Request $request, Resource $resource) {
        // delete resource
        if (!$resource->delete())
            // redirect with errors
            return back()
                ->withErrors( $resource->errors() );

        // redirect to list
        return redirect()->route('backend.pos');
    }

}
