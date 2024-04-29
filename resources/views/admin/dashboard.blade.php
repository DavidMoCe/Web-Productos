<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                    <h1>Bienvenido {{ auth()->user()->name }}</h1>
                    
                    @if (!isset($tipo))
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __("Elige el movil") }}
                        </div>
                    @endif
                    
                    
                    @if (!isset($modelo) && isset($tipo))
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __("Elige el modelo") }}
                        </div>
                    @endif
                    
                    @if (isset($tipo) && isset($modelo))
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __("Se muestran los modelos") }}
                        </div>
                    @endif
                        
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
