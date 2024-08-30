<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Installment;
use App\Models\InstallmentValue;
use App\Models\OrderItem;

use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{





    public function index()
    {
        //
    }

    
    public function create()
    {
        $userId = Auth::id();
        $customers = Customer::where('user_id', $userId)->get();
        $products = Product::where('user_id', $userId)->get();
        
        
        return view('admin.order.create', compact('customers', 'products'));
    }

    
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
        
        $orderItems = json_decode($request->sold_items, true); // Decodifica o JSON em um array
        foreach ($orderItems as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item['product_id'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->save();
}
    
        return redirect()->back()->with('success', 'Pedido criado com sucesso!');
        
    }

        


    
    public function show($userId)
{
    
    $orders = Order::where('user_id', $userId)->get();

    $customers = Customer::where('user_id', $userId)->get();

    $orderIds = $orders->pluck('id');

    $customersIds = $customers->pluck('id');

    $installments = Installment::whereIn('customer_id', $customersIds)->get();

    $installmentIds = $installments->pluck('id');
    $installment_values = InstallmentValue::whereIn('installment_id', $installmentIds)->get();

    $order_items = OrderItem::whereIn('order_id', $orderIds)->get();

    $productIds = $order_items->pluck('product_id');
    $products = Product::whereIn('id', $productIds)->get();

    return view('admin.order.show', compact('orders', 'customers', 'products', 'installments', 'installment_values', 'order_items'));
}
    
    public function edit(Order $order)
    {

        

    }

    
    public function update(Request $request, Order $order)
    {
        Log::info('Atualizando pedido', [
            'order_id' => $order->id,
            'request_order_id' => $request->input('order_id'),
            'request_customer' => $request->input('customer'),
            'request_installmentValue' => $request->input('installmentValue'),
            'request_installmentDueDate' => $request->input('installmentDueDate'),
            'request_modalTotal' => $request->input('modalTotal'),
        ]);

        if ($order->id != $request->order_id) {
            return redirect()->back()->withErrors(['error' => 'Pedido inválido']);
        }
        // Atualiza os valores do pedido
        $order->customer_id = $request->customer;
        $order->save();
    
        // Remove os valores antigos das parcelas
        InstallmentValue::where('installment_id', $request->order->installment_id)->delete();
    
        // Adiciona os novos valores das parcelas
        foreach ($request->installmentValue as $index => $value) {
            $installmentValue = new InstallmentValue();
            $installmentValue->installment_id = $request->order->installment_id;
            $installmentValue->value = $value;
            $installmentValue->due_date = $request->installmentDueDate[$index];
            $installmentValue->save();
        }
    
        // Atualiza o total na tabela installments
        $installment = Installment::where('id', $order->installment_id)->first();
        if ($installment) {
            $installment->total_amount = $request->modalTotal;
            $installment->save();
        }
        return redirect()->back()->with('success', 'Pedido atualizado com sucesso!');

    }



 
    public function destroy(Order $order)
{
    // Exclua todos os registros relacionados na tabela OrderItems
    OrderItem::where('order_id', $order->id)->delete();

    // Exclua os valores das parcelas relacionados
    // Verifique se há registros relacionados antes de tentar excluir
    if ($order->installment_id) {
        InstallmentValue::where('installment_id', $order->installment_id)->delete();
    }
    $id = $order->installment_id; 
    $order::where('installment_id', $order->installment_id)->delete();
    // Se o pedido possui uma parcela associada, exclua o registro da tabela Installments
    
    Installment::where('id', $id)->delete();

    // Finalmente, exclua o pedido
    

    return redirect()->back()->with('success', 'Pedido excluído com sucesso!');
}
}
