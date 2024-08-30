<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edição de Produtos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Digite os novos dados:</h3>
                
                <form action=" {{ route('products.update', $product)}}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="nome" class="block text-gray-700 font-semibold">Nome:</label>
                        <input type="text" id="nome" value="{{$product->name}}" name="name" class="form-input mt-1 block w-full" placeholder="Digite seu nome">
                    </div>
                    
                    <div class="mb-4">
                        <label for="unit_price" class="block text-gray-700 font-semibold">Preco Un.:</label>
                        <input type="text" id="unit_price" value="{{$product->unit_price}}" name="unit_price" class="form-input mt-1 block w-full" placeholder="Digite seu CPF">
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
