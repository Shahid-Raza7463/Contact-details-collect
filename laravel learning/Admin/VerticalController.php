<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Vertical;
use Illuminate\Http\Request;

class VerticalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = vertical::all();
        return view('Admin.vertical.index_vertical', ['data' => $data]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('Admin.vertical.create_vertical');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' =>  'required|unique:verticals|max:255',
            'status' => 'required',
            'network_count' => 'required',
        ]);

        $verticals = $request->all();
        Vertical::create($verticals);
        return redirect('verticals')->with('message', 'Your data successfully added');
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
        $vertical = Vertical::find($id);
        if ($vertical) {
            return view('Admin.vertical.update_vertical', $vertical);
        }
        return ['success' => false, 'msg' => 'Vertical Not Found !!!'];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' =>  "required|unique:verticals,id," . $id . "|max:255",
            'status' => 'required',
            'network_count' => 'required',
        ]);

        $verticals = Vertical::find($id);
        if (!$verticals) {
            return ['success' => false, 'msg' => 'Vertical Not Found !!!'];
        }

        $verticals->title = $request->input('title');
        $verticals->status = $request->input('status');
        $verticals->save();
        return redirect('verticals')->with('message', 'Your data successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $verticals = Vertical::find($id);

        if (!$verticals) {
            return ['success' => false, 'msg' => 'Vertical Not Found !!!'];
        }



        $verticals->delete();
        return redirect('verticals')->with('message', 'Your data successfully deleted');
        // dd($verticals);
    }
}
 // dd($data);