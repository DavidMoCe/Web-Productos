<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Billing Address') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your billing address.") }}
        </p>
    </header>
    <form action="{{ route('profile.updateBilling') }}" method="POST" class="mt-6 space-y-6">
        @csrf                    
        <!-- Mostrar los campos nombre y apellido del usuario logueado -->
        <div>
            <x-input-label for="company" :value="__('Company')" />
            <x-text-input id="company" name="company" type="text" class="mt-1 block w-full"
                :value="old('company', $user->direccionFacturacion->empresa)" autocomplete="company" />
            <x-input-error class="mt-2" :messages="$errors->get('company')" />
        </div>
        <div>
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full"
                :value="old('address', $user->direccionFacturacion->direccion_1)" required autocomplete="address" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>
        <div>
            <x-input-label for="address_2" :value="__('Address_2')" />
            <x-text-input id="address_2" name="address_2" type="text" class="mt-1 block w-full"
                :value="old('address_2', $user->direccionFacturacion->direccion_2)" autocomplete="address_2" />
            <x-input-error class="mt-2" :messages="$errors->get('address_2')" />
        </div>
        <div>
            <x-input-label for="postal_code" :value="__('Postal Code')" />
            <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block w-full"
                :value="old('postal_code', $user->direccionFacturacion->codigo_postal)" required autocomplete="postal_code" />
            <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
        </div>
        <div>
            <x-input-label for="city" :value="__('City')" />
            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full"
                :value="old('city', $user->direccionFacturacion->ciudad)" required autocomplete="city" />
            <x-input-error class="mt-2" :messages="$errors->get('city')" />
        </div>
        <div>
            <x-input-label for="country" :value="__('Country')" />
            <x-text-input id="country" name="country" type="text" class="mt-1 block w-full"
                :value="old('country', $user->direccionFacturacion->pais)" required autocomplete="country" />
            <x-input-error class="mt-2" :messages="$errors->get('country')" />
        </div>
        <div>
            <x-input-label for="nif_dni" :value="__('NIF/DNI')" />
            <x-text-input id="nif_dni" name="nif_dni" type="text" class="mt-1 block w-full"
                :value="old('nif_dni', $user->direccionFacturacion->nif_dni)" required autocomplete="nif_dni" />
            <x-input-error class="mt-2" :messages="$errors->get('nif_dni')" />
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>        
            @if (session('status') === 'shipping-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>