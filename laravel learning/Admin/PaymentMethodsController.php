<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\PaymentMethodModel;
use Illuminate\Http\Request;

class PaymentMethodsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = PaymentMethodModel::all();
        return view('Admin.paymentmethod.index_paymentMethods', ['data' => $data]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('Admin.paymentmethod.create_paymentMethods');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $paymentMethod  = $request->all();
        PaymentMethodModel::create($paymentMethod);
        return redirect('paymentMethod')->with('message', 'Your data successfully added');
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
        $data['paymentMethod'] = PaymentMethodModel::find($id);
        $data['id'] = $id;

        return view('Admin.paymentmethod.update_paymentMethods', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' =>  "required|unique:payment_lists,id," . $id . "|max:255",
        ]);

        $paymentMethod = PaymentMethodModel::find($id);
        $paymentMethod->name = $request->input('name');
        $paymentMethod->save();
        return redirect('paymentMethod')->with('message', 'Your data successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $paymentMethod = PaymentMethodModel::find($id);
        $paymentMethod->delete();
        return redirect('paymentMethod')->with('message', 'Your data successfully deleted');
    }
}
