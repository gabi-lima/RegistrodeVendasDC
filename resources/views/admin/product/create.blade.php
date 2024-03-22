<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cadastro de Produtos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Preencha os dados do Produto abaixo:</h3>
                <form action=" {{ route('products.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" class="form-input mt-1 block w-full" value="{{Auth::user()->id}}">

                    <div class="mb-4">
                        <label for="nome" class="block text-gray-700 font-semibold">Nome:</label>
                        <input type="text" id="nome" name="name" class="form-input mt-1 block w-full" placeholder="Digite o nome do produto:">
                    </div>
                    
                    <div class="mb-4">
                        <label for="preco" class="block text-gray-700 font-semibold">Preço Unitário:</label>
                        <input type="text" id="preco" name="unit_price" class="form-input mt-1 block w-full" placeholder="Digite o Preço Unitário: ">
                    </div>
                    
                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Enviar</button>
                    </div>
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
