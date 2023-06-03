<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\PaymentFrequencyModel;
use Illuminate\Http\Request;

class PaymentsFrequencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = PaymentFrequencyModel::all();
        return view('Admin.paymentfrequency.index_paymentFrequency', ['data' => $data]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('Admin.paymentfrequency.create_paymentFrequency');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
        ]);

        $paymentFrequency  = $request->all();
        PaymentFrequencyModel::create($paymentFrequency);
        return redirect('paymentFrequency')->with('message', 'Your data successfully added');
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
        $data['paymentFrequency'] = PaymentFrequencyModel::find($id);
        $data['id'] = $id;

        return view('Admin.paymentfrequency.update_paymentFrequency', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' =>  "required|unique:net_frequency_lists,id," . $id . "|max:255",
        ]);

        $paymentFrequency = PaymentFrequencyModel::find($id);
        $paymentFrequency->name = $request->input('name');
        $paymentFrequency->save();
        return redirect('paymentFrequency')->with('message', 'Your data successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $paymentFrequency = PaymentFrequencyModel::find($id);
        $paymentFrequency->delete();
        return redirect('paymentFrequency')->with('message', 'Your data successfully deleted');
    }
}
