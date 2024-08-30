<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gerar Venda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Preencha os dados da Venda abaixo:</h3>
                <form action=" {{ route('orders.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" class="form-input mt-1 block w-full rounded" value="{{Auth::user()->id}}">

                    <div class="mb-4">
                        <label for="customer" class="block text-gray-700 font-semibold">Cliente:</label>
                        <select id="customer" name="customer" class="form-select mt-1 block w-full py-2 px-2 text-lg rounded">
                            <option value="">Escolha o cliente</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="product" class="block text-gray-700 font-semibold">Produto:</label>
                        <select id="product"  class="form-select mt-1 block w-full py-2 px-2 text-lg rounded">
                            <option value="">Escolha o Produto</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-unit-price="{{ $product->unit_price }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4 flex items-end ">
                        <div class="mr-4">
                            <label for="quantity" class="block text-gray-700 font-semibold">Quantidade:</label>
                            <input type="number" id="quantity" name="quantity" class="form-input mt-1 block rounded" value="1" placeholder="Digite a Quantidade">
                        </div>
                        
                        <div class="mr-4">
                            <label for="unit_price" class="block text-gray-700 font-semibold">Valor Unitário:</label>
                            <input type="number" id="unit_price" name="unit_price" class="form-input mt-1 block rounded" placeholder="Digite o Valor Unitário">
                        </div>
                        <div class="mr-4">
                            <label for="subtotal" class="block text-gray-700 font-semibold">Subtotal:</label>
                            <input type="number" id="subtotal" name="subtotal" class="form-input mt-1 block rounded" placeholder="Digite o Subtotal">
                        </div>
                            <button type="button" id="add-item"class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-5 rounded ">Adicionar Item</button>
                        
                        </div>
                        <!-- Inputs escondidos -->
                        <div id="items-container">
                            <input type="hidden" name="user_id" class="form-input mt-1 block w-full" value="{{Auth::user()->id}}">
                            <input type="hidden" name="customer_id" id="customer_id" value="">
                            <input type="hidden" name="installment_id" id="installment_id" value="">
                            <input type="hidden" name="installment_value" id="installment_value" value="">
                            <input type="hidden" name="due_date" id="due_date" value="">
                            <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="product_id" id="product_id" value="">
                            <input type="hidden" name="product_quantity" id="product_quantity" value="">
                        </div>

                            

                        
                        
                            <!-- parcelas -->
                        <div class="mr-4 ">
                            <div class="flex"><label for="installments" class="block text-gray-700 font-semibold mr-2">Parcelar?</label>
                                <input type="checkbox" id="parcelCheckbox" class="form-checkbox mt-1 block rounded"> 
                            </div>
                            <input type="number" id="installments" name="installments" value="1.0"  class="disabled:opacity-75 form-input mt-1 block rounded disabled:bg-slate-50" disabled placeholder="Digite a Quantidade">
                            <label for="total" class="block text-gray-700 font-semibold">Total:</label>
                            <input type="number" id="total" name="total" value="0.00" class="form-input mt-1 block rounded" readonly>
                            <div class="mt-6">
                                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Adicionar</button>
                            </div>
                            <div id="installments-container" class="mt-4 p-4 border rounded-md"></div>
                            <div id="items-list" class="border-gray-200 pt-4">
                        </div>
                        
                        
                       
                        
                    
                        

                    
                </form>
                
            </div>
            
        </div>
        </div>
        
    </div>
    <script>
    let soldItems = [];

function addItemToSoldItems(productId, quantity) {
    soldItems.push({ product_id: productId, quantity: quantity });
    updateSoldItemsInput(); 
}

function updateSoldItemsInput() {
    const soldItemsInput = document.querySelector('#soldItems');
    soldItemsInput.value = JSON.stringify(soldItems);
}

function addNewItem(productId, quantity) {
    addItemToSoldItems(productId, quantity);

    var productName = $('#product option:selected').text();
    var newItem = `<div class="mb-2">
        <p><strong>Produto: ${productName}</strong></p>
        <p>Quantidade: ${quantity}</p>
        <button class="remove-btn bg-red-500 text-white font-bold py-1 px-3 rounded">Remover</button>
    </div>`;

    $('#items-list').append(newItem);
}
$(document).ready(function() {
    $('#add-item').click(function() {
        var productId = $('#product').val();
        var quantity = $('#quantity').val();

        if (productId && quantity) {
            addNewItem(productId, quantity);
        } else {
            alert('Por favor, preencha todos os campos antes de adicionar o item.');
        }
    });

    $('#items-container').append('<input type="hidden" id="soldItems" name="sold_items" value="">');
});

        $('#product').change(function() {
            var productId = $(this).val(); 
            $('#product_id').val(productId); 
        });
        $('#customer').change(function() {
            var customer_id = $(this).val(); 
            $('#customer_id').val(customer_id); 
        });
        
        function updateHiddenInputs() {
            
            $('#customer_id').val($('#customer').val());
            $('#product_id').val($('#product').val());
            $('#product_quantity').val($('#quantity').val());
            
            if ($('#installments').is(':enabled')) {
                
                $('#installment_value').val($('#installments').val());
                
                $('#due_date').val($('#due_date').val());
            }
        }


        $('form').submit(function() {
            updateHiddenInputs();
        });

         
         $(document).ready(function() {
            
            function addInstallmentsInputs(numInstallments) {
                if ($('#items-list').children().length === 0) {
                    alert('Por favor, adicione pelo menos um item antes de definir as parcelas.');
                    return;
                }

                $('#installments-container').empty();

                
                var totalValue = parseFloat($('#total').val());

                if (isNaN(totalValue) || totalValue <= 0) {
                    alert('Por favor, insira um valor total válido antes de definir as parcelas.');
                    return;
                }



                var installmentValue = totalValue / numInstallments;

                var today = new Date();

                var currentMonth = today.getMonth() + 1;
                var currentYear = today.getFullYear();

                var installmentMonth = (currentMonth + i - 1) % 12;
                var installmentYear = currentYear + Math.floor((currentMonth + i - 1) / 12);

                var installmentDate = new Date(installmentYear, installmentMonth, 1);


                for (var i = 1; i <= numInstallments; i++) {
                    installmentMonth = (currentMonth + i - 1) % 12;
                    installmentYear = currentYear + Math.floor((currentMonth + i - 1) / 12);
                    installmentDate = new Date(installmentYear, installmentMonth, 1);
                    var installmentInput = '<div class="mb-4">' +
                        '<label for="installment' + i + '" class="block text-gray-700 font-semibold">Parcela ' + i + ':</label>' +
                        '<input type="number" id="installment' + i + '" name="installment' + i + '" class="form-input mt-1 block rounded" placeholder="Valor da Parcela ' + i + '" value="' + installmentValue.toFixed(2) + '">' +
                        '<label for="date' + i + '" class="block text-gray-700 font-semibold">Data de Vencimento Parcela ' + i + ':</label>' +
                        '<input type="date" id="date' + i + '" name="date' + i + '" class="form-input mt-1 block rounded" value="' + installmentDate.toISOString().slice(0,10) + '">' +
                        '</div>';

                    $('#installments-container').append(installmentInput);
                }
            }

            $('#installments').change(function() {
                var numInstallments = parseInt($(this).val());

                if (!isNaN(numInstallments) && numInstallments > 0) {
                    addInstallmentsInputs(numInstallments);
                }
            });
        });
        
   



        $('#product').change(function() {
            var unitPrice = $(this).find(':selected').data('unit-price');
            $('#unit_price').val(unitPrice);
            console.log(unitPrice);
            updateSubtotal();
        });

        function updateSubtotal() {
            var quantity = $('#quantity').val();
            var unitPrice = $('#unit_price').val();
            var subtotal = parseFloat(quantity) * parseFloat(unitPrice);
            $('#subtotal').val(subtotal.toFixed(2)); 
        }

        $('#quantity').change(function() {
            updateSubtotal();
        });

        $('#unit_price').change(function() {
            updateSubtotal();
        });

        $(document).ready(function() {
            $('#parcelCheckbox').change(function() {
                if ($(this).is(':checked')) {
                    if ($('#items-list').children().length === 0) {
                    alert('Por favor, adicione pelo menos um item antes de definir as parcelas.');
                    $(this).prop('checked', false)
                    return;
                }
                    
                    $('#installments').prop('disabled', false);
                } else {
                    
                    $('#installments').prop('disabled', true);
                }
            });
        });
        $(document).ready(function() {
    var itemId = 1;

    // Funcao para adicionar itens ao clicar no botao
    $('#add-item').click(function() {
        var quantity = $('#quantity').val(); 
        var unitPrice = $('#unit_price').val(); 
        var subtotal = $('#subtotal').val(); 
        var productName = $('#product option:selected').text();
        var productId = $('#product').val();

        if (quantity === '' || unitPrice === '' || $('#product').val() === '') {
            alert('Por favor, preencha todos os campos antes de adicionar o item.');
            return
        }
        addNewItem(productId, quantity);
        
        var newItem = '<div class="mb-2">' +
            '<p><strong>Produto: ' + productName + '</strong></p>' +
            '<p>Quantidade: ' + quantity + '</p>' +
            '<p>Preco Unitario: ' + unitPrice + '</p>' +
            '<p>Subtotal: ' + subtotal + '</p>' +
            '<button class="edit-btn bg-blue-500 text-white font-bold py-1 px-3 rounded mr-2">Editar</button>' + 
            '<button class="remove-btn bg-red-500 text-white font-bold py-1 px-3 rounded">Remover</button>' + 
            '</div>';

        $('#items-list').append(newItem); 
        updateTotal(subtotal);
        // Adiciona o novo item à lista de itens vendidos

        

    });

    // Editar botao
    $(document).on('click', '.edit-btn', function() {
        
    });

    // Remover botao 
    $(document).on('click', '.remove-btn', function() {
        var subtotalToRemove = parseFloat($(this).closest('.mb-2').find('p:nth-child(4)').text().split(': ')[1]);
        $(this).closest('.mb-2').remove();
        updateTotal(-subtotalToRemove); 
    });

    
    });
    // atualiza o input total 

    function updateTotal(subtotalChange) {
    var total = parseFloat($('#total').val() || 0) + parseFloat(subtotalChange || 0);
    $('#total').val(total.toFixed(2));
}

    
        </script>
    
</x-app-layout>
