    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Produtos') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Lista de Produtos</h3>
                    @foreach($products as $product)
                        <div class="mb-4 border-b border-gray-200">
                            <p class="mb-2"><strong>Nome:</strong> {{$product->name}}</p>
                            <p class="mb-2"><strong>Preco Un.:</strong> {{$product->unit_price}}</p>
                            <a href="{{ route('products.edit', $product) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4  rounded">
                            Editar
                        </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
            @if(session('msg'))
                <script>
                        alert({{session('msg')}})
                </script>
            @endif
        
    </x-app-layout>
