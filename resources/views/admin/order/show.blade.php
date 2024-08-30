@php
$order = (object) [
    'id' => 3,
    
];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vendas Registradas') }}
        </h2>
    </x-slot>
    <div class="fixed z-10 inset-0 overflow-y-auto" style="display: none;" id="orderModal">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            @if(session('msg'))
                        <p class="text-green-500"><strong>{{session('msg')}}</p>
            @endif

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg font-semibold mb-4">Detalhes do Pedido</h3>
                            <form id="form" action="{{ route('orders.update', $order->id) }}" method="POST">
                                @csrf 
                                @method('PUT')
                            <input type="hidden" name="customer_id" id="customerId" value="" >
                            <input type="hidden" name="order_id" id ="order_id" value="{{ $order->id }}">
                            <input type="hidden" name="sold_items" id="sold_items" value ="">

                            <div class="mb-4">
                                <label for="modalOrderId" class="block text-sm font-medium text-gray-700">ID do Pedido</label>
                                <input type="text" name="modalOrderId" id="modalOrderId" class="mt-1 p-2 border rounded-md w-full" readonly>
                            </div>
                            <select id="customer" name="customer" class="form-select mt-1 block w-full py-2 px-2 text-lg rounded">
                                <option value="">Escolha o cliente</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            
                            <div class="mb-4">
                                <label for="modalProducts" class="block text-sm font-medium text-gray-700">Produtos</label>
                                <textarea name="modalProducts" id="modalProducts" class="mt-1 p-2 border rounded-md w-full h-24" ></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="modalTotal" class="block text-sm font-medium text-gray-700">Total</label>
                                <input type="text" name="modalTotal" id="modalTotal" oninput="updateInstallmentInputs()" class="mt-1 p-2 border rounded-md w-full " >
                            </div>
                            <div>
                                
                            </div>

                            <div class="mb-4">
                                <label for="numInstallments" class="block text-sm font-medium text-gray-700">Número de Parcelas</label>
                                <input type="number" id="numInstallments" name="numInstallments" class="mt-1 p-2 border rounded-md w-full" min="1" value="" oninput="updateInstallmentInputs()">
                            </div>
                            <div class="mb-4" id='installmentInputs'>

                                
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm" onclick="saveOrder()">
                        Salvar
                    </button>
                </form>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm" onclick="closeModal()">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Lista de Vendas</h3>
                
                <div class="overflow-x-auto">
                    
                
                <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="border px-4 py-2">ID do Pedido</th>
                    <th class="border px-4 py-2">Cliente</th>
                    <th class="border px-4 py-2">Produtos</th>
                    <th class="border px-4 py-2">Total</th>
                    <th class="border px-4 py-2">Parcelas</th>
                    <th class="border px-4 py-2">Data da Próxima Parcela</th>
                    <th class="border px-4 py-2">Ações</th>
                </tr>
            </thead>

            <tbody class="block md:table-row-group">
                @foreach ($orders as $order)
                <tr class="border hover:bg-gray-100" data-id="{{ $order->id }}">
                    <td class="p-4">{{ $order->id }}</td>
                    <td class="p-4">
                        @foreach($customers as $customer)
                            @if($customer->id == $order->customer_id)
                                {{ $customer->name }}
                            @endif
                        @endforeach
                    </td>
                    <td class="p-4">
                        @foreach($order_items as $item)
                            @if($item->order_id == $order->id)
                                @foreach($products as $product)
                                    @if($product->id == $item->product_id)
                                        {{ $product->name }},
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </td>
                    <td class="p-4">
                        @foreach($installments as $installment)
                            @if($installment->id == $order->id)
                                {{ $installment->total_amount }}
                            @endif
                        @endforeach
                    </td>
                    <td class="p-4">
                        @foreach($installment_values as $value)
                            @if($value->installment_id == $order->id)
                                {{ $value->value }},
                            @endif
                        @endforeach
                    </td>
                    <td class="p-4">
                        @foreach($installment_values as $value)
                            @if($value->installment_id == $order->id)
                                {{ \Carbon\Carbon::parse($value->due_date)->format('d/m') }}
                                @break
                            @endif
                        @endforeach
                    </td>
                    <td class="p-4">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="openModal({{ json_encode($order) }}, {{ json_encode($customers) }}, {{ json_encode($order_items) }}, {{ json_encode($products) }}, {{ json_encode($installments) }}, {{ json_encode($installment_values) }})">
                            Editar
                        </button>
                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Tem certeza que deseja excluir este pedido?')">
                                Excluir
                            </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
                </div>
            </div>
        </div>
    </div>
    <script>
function openModal(order, customers, orderItems, products, installments, installmentValues) {
    let orderId = order.id;
    console.log(orderId);
    document.getElementById('modalOrderId').value = orderId;
    document.querySelector('input[name="order_id"]').value = orderId; // Adicionado

    let numExistingInstallments = installmentValues.length
    document.getElementById('numInstallments').value = numExistingInstallments; 
    
    let customerName = customers.find(customer => customer.id === order.customer_id).name;
    let customerSelect = document.getElementById('customer');
    for(let i = 0; i < customerSelect.options.length; i++) {
        if(customerSelect.options[i].text === customerName) {
            customerSelect.selectedIndex = i;
            break;
        }
    }

    let productNames = orderItems
        .filter(item => item.order_id === orderId)
        .map(item => products.find(product => product.id === item.product_id).name)
        .join(', ');
    document.getElementById('modalProducts').value = productNames;

    let totalAmount = parseFloat(installments.find(installment => installment.id === order.installment_id).total_amount);
    document.getElementById('modalTotal').value = `${totalAmount.toFixed(2)}`;


    let installmentValuesList = installmentValues
        .filter(value => value.installment_id === order.installment_id);

    let installmentInputs = '';
    installmentValuesList.forEach((value, index) => {
        installmentInputs += `
            <div class="flex items-center mb-2">
                <input type="number" name="installmentValue[]" value="${value.value}" class="installment-value mt-1 p-2 border rounded-md w-1/2 mr-2" ">
                <input type="date" name="installmentDueDate[]" value="${value.due_date}" class="mt-1 p-2 border rounded-md w-1/2">
            </div>
        `;
    });

    let installmentInputsContainer = document.getElementById('installmentInputs');
    if (installmentInputsContainer) {
        installmentInputsContainer.innerHTML = '<label class="block text-sm font-medium text-gray-700">Parcelas</label>' + installmentInputs;
    }

    document.getElementById('orderModal').style.display = 'block';
    
}

function saveOrder(order) {
    

    //Obtenha os elementos de entrada do produto e quantidade
    let productSelects = document.querySelectorAll('.product-select');
    let quantityInputs = document.querySelectorAll('.quantity');

    // Inicialize a lista de itens vendidos
    let soldItems = [];

    // Percorra as seleções de produtos e quantidades para compilar a lista de itens vendidos
    productSelects.forEach((select, index) => {
        let productId = select.value;
        let quantity = quantityInputs[index].value;

        // Crie um objeto representando o item vendido e adicione à lista
        soldItems.push({
            product_id: productId,
            quantity: quantity
        });
    }); 

    // Armazene a lista de itens vendidos como uma string JSON em um campo oculto do formulário
    document.getElementById('sold_items').value = JSON.stringify(soldItems);

    // Obtenha o valor total do pedido
    let totalAmount = parseFloat(document.getElementById('modalTotal').value.replace('R$: ', '').replace(',', '.'));

    // Valide o valor total
    if (isNaN(totalAmount)) {
        alert('O valor total é inválido. Corrija antes de salvar.');
        return;
    }

    // Calcule a soma das parcelas
    let installmentValuesInputs = document.querySelectorAll('.installment-value');
    let sumOfInstallments = Array.from(installmentValuesInputs)
        .map(input => parseFloat(input.value.replace(',', '.')))
        .reduce((sum, value) => sum + (isNaN(value) ? 0 : value), 0);

    // Verifique se a soma das parcelas corresponde ao valor total
    if (totalAmount !== sumOfInstallments) {
        
        console.log(sumOfInstallments);
        alert('O valor das parcelas não corresponde ao valor total do pedido. Corrija antes de salvar.');
        return;
    } 

    // Se tudo estiver correto, defina a ação do formulário e submeta-o
    let orderId = document.querySelector('input[name="order_id"]').value;
    let form = document.getElementById('form');
    console.log('Saving order with ID:', orderId); // Adicionado para depuração

    form.action = `/orders/${orderId}`;

    form.submit();
}
function closeModal() {
    document.getElementById('orderModal').style.display = 'none';
}

function updateInstallmentInputs() {
    let numInstallments = document.getElementById('numInstallments').value;
    let installmentInputsContainer = document.getElementById('installmentInputs');
    let subtotal = parseFloat(document.getElementById('modalTotal').value);
    
    installmentInputsContainer.innerHTML = ''; // Limpa os inputs existentes
    
    if (!isNaN(subtotal) && numInstallments > 0) {
        let newInstallments = (subtotal / numInstallments).toFixed(2); // Divide o subtotal pelas parcelas e formata com 2 casas decimais
        
        let currentDate = new Date(); // Data atual

        for (let i = 0; i < numInstallments; i++) {
            currentDate.setMonth(currentDate.getMonth() + 1);
            
            let formattedDate = currentDate.toISOString().split('T')[0];
            
            installmentInputsContainer.innerHTML += `
                <div class="flex items-center mb-2">
                    <input type="number" name="installmentValue[]" class="installment-value mt-1 p-2 border rounded-md w-1/2 mr-2" value="${newInstallments}" placeholder="Valor da Parcela ${i + 1}">
                    <input type="date" name="installmentDueDate[]" class="mt-1 p-2 border rounded-md w-1/2" value="${formattedDate}">
                </div>
            `;
        }
    } else {
        alert('Por favor, insira um valor total válido e um número de parcelas maior que 0.');
    }
}

    </script>

    
    
    
    
    
    
</x-app-layout>
