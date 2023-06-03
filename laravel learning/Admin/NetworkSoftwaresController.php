<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\NetworkSoftwareModel;
use Illuminate\Http\Request;

class NetworkSoftwaresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = NetworkSoftwareModel::all();
        return view('Admin.networksoftware.index_networkSoftwares', ['data' => $data]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('Admin.networksoftware.create_networkSoftwares');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
        ]);

        $networkSoftware  = $request->all();
        NetworkSoftwareModel::create($networkSoftware);
        return redirect('networkSoftware')->with('message', 'Your data successfully added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = [];
        $data['networkSoftware'] = NetworkSoftwareModel::find($id);
        $data['id'] = $id;

        return view('Admin.networksoftware.update_networkSoftwares', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' =>  "required|unique:network_softwares,id," . $id . "|max:255",
        ]);

        $networkSoftware = NetworkSoftwareModel::find($id);
        $networkSoftware->name = $request->input('name');
        $networkSoftware->save();
        return redirect('networkSoftware')->with('message', 'Your data successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $networkSoftware = NetworkSoftwareModel::find($id);
        $networkSoftware->delete();
        return redirect('networkSoftware')->with('message', 'Your data successfully deleted');
    }
}
