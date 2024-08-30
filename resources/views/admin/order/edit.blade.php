<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cadastro de Clientes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Digite os novos dados:</h3>
                <input type="hidden" name="customer_id" value="">
                <form action=" {{ route('orders.update', $order)}}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="nome" class="block text-gray-700 font-semibold">Nome:</label>
                        <input type="text" id="nome" value="{{$customers->name}}" name="name" class="form-input mt-1 block w-full" placeholder="Digite seu nome" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="cpf" class="block text-gray-700 font-semibold">CPF:</label>
                        <input type="text" id="cpf" value="{{$customers->cpf}}" name="cpf" class="form-input mt-1 block w-full" placeholder="Digite seu CPF" required>
                    </div>
                    <div class="mb-4">
                        <label for="cpf" class="block text-gray-700 font-semibold">CPF:</label>
                        <input type="text" id="cpf" value="{{$customers->cpf}}" name="cpf" class="form-input mt-1 block w-full" placeholder="Digite seu CPF" required>
                    </div>
                    
                    <div class="mt-6">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Salvar
                    </button>
                    <div class="mt-2">
                        @if(session('msg'))
                        <p class="text-green-500"><strong>{{session('msg')}}</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
