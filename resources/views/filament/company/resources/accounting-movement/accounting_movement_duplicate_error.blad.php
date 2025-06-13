<x-filament::modal id="error-modal" width="lg">
    <x-slot name="header">
        <h2 class="text-lg font-medium">{{ __('Error Copying Movement') }}</h2>
    </x-slot>

    <div class="p-6">
        <p class="text-red-600">{{ $errorMessage }}</p>
    </div>

    <x-slot name="footer">
        <x-filament::button color="gray" wire:click="$dispatch('close-modal', { id: 'error-modal' })">
            {{ __('Close') }}
        </x-slot>
</x-filament::modal>
