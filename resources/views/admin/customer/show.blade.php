    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Clientes') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Lista de Clientes</h3>
                    @foreach($customers as $customer)
                        <div class="mb-4 border-b border-gray-200">
                            <p class="mb-2"><strong>Nome:</strong> {{$customer->name}}</p>
                            <p class="mb-2"><strong>CPF:</strong> {{$customer->cpf}}</p>
                            <a href="{{ route('customers.edit', $customer) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Editar
                        </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-app-layout>
