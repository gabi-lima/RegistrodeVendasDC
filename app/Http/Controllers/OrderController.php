<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Installment;
use App\Models\InstallmentValue;
use App\Models\OrderItem;


use Illuminate\Http\Request;

class OrderController extends Controller
{





    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        
        return view('admin.order.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    $customerId = $request->customer_id;
    $totalAmount = $request->total; 
    $numInstallments = $request->installments;
    $installmentAmount = $totalAmount / $numInstallments;
    $firstDueDate = date('Y-m-d', strtotime('+1 month'));
    $installment = new Installment();
    $installment->customer_id = $customerId; 
    $installment->total_amount = $request->total; 

    $installment->save();

    $installmentId = $installment->id;


    for ($i = 0; $i < $numInstallments; $i++) {
        $dueDate = date('Y-m-d', strtotime("+$i month", strtotime($firstDueDate)));

        $installment_value = new InstallmentValue();

        $installment_value->value = $installmentAmount;
        $installment_value->due_date = $dueDate;
        $installment_value->installment_id = $installmentId; 
        $installment_value->save();
        
    }
        $order = new Order();
        $order->user_id = $request->user_id;
        $order->customer_id = $request->customer_id;
        $order->installment_id = $installmentId;
        $order->save();
        
    
        return redirect()->back()->with('success', 'Pedido criado com sucesso!');
        
    }

        


    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $customers = Customer::all();
        $products = Product::all();
        $orders = Order::all();
        $installments = Installment::all();
        $installment_values = InstallmentValue::all();
        $order_items = OrderItem::all();
        
    
        return view('admin.order.show', compact('order', 'orders', 'customers', 'products', 'installments', 'installment_values', 'order_items'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {

        

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $order = Order::find($order->id);
    
        // Atualizar os valores do pedido
        $order->customer_id = $request->customer;
    
        // Salvar as mudanças no pedido
        $order->save();

        
    
        // Atualizar os valores das parcelas
        InstallmentValue::where('installment_id', $order->installment_id)->delete();
    
        for ($i = 0; $i < count($request->installmentValue); $i++) {
            $installmentValue = new InstallmentValue;
    
            $installmentValue->installment_id = $order->installment_id;
            $installmentValue->value = $request->installmentValue[$i];
            $installmentValue->due_date = $request->installmentDueDate[$i];
            
            $installmentValue->save();
        }
    
        // Atualizar o total na tabela installments
        $installment = Installment::findOrFail($order->installment_id);
        $installment->total_amount = $request->modalTotal;
        $installment->save();
    
        return redirect()->back()->with('success', 'Pedido atualizado com sucesso!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
