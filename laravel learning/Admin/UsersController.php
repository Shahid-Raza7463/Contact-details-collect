<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\UserModel;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = UserModel::all();
        return view('Admin.users.index_users', ['data' => $data]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $users  = $request->all();
        UserModel::create($users);
        return redirect('users')->with('message', 'Your data successfully added');
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
        $data['users'] = UserModel::find($id);
        $data['id'] = $id;

        return view('Admin.users.update_users', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' =>  "required|unique:net_frequency_lists,id," . $id . "|max:255",
            'email' => 'required',
        ]);

        $users = UserModel::find($id);
        // only name and email want to updated in database
        $users->name = $request->input('name');
        $users->email = $request->input('email');
        $users->save();
        return redirect('users')->with('message', 'Your data successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $users = UserModel::find($id);
        $users->delete();
        return redirect('users')->with('message', 'Your data successfully deleted');
    }
}
