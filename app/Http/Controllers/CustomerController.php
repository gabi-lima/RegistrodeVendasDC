<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $customer = new Customer();
        $customer->user_id = $request->user_id;
        $customer->name = $request->name;
        $customer->cpf = $request->cpf;
        
        $customer->save();

        return redirect()->route('customers.create')->with('msg', 'Cliente cadastrado com sucesso');

    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $user = User::where('id', $customer->id)->first();
        $customers = $user->customers()->get();
        return view('admin.customer.show', compact('customers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)

    {
        
            return view('admin.customer.edit', compact('customer'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $customer->name = $request->name;
        $customer->cpf = $request->cpf;

        $customer->save();

    return redirect()->route('customers.show', ['customer' => auth()->user()->id])->with('success', 'Cliente atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
    
    
    
}
