<div>
    @if ($showModal)
        <x-filament::modal>
            <x-slot name="header">
                <h2 class="text-xl font-bold">{{ __('Unbalanced Movement') }}</h2>
            </x-slot>

            <div>
                <p>{{ __('The total debit and credit amounts do not match.') }}</p>
                <p>{{ __('Debit Total: ') }} {{ number_format($debitTotal, 2, '.', ',') }}</p>
                <p>{{ __('Credit Total: ') }} {{ number_format($creditTotal, 2, '.', ',') }}</p>
                <p>{{ __('Do you want to save the movement as INVALID?') }}</p>
            </div>

            <x-slot name="footer">
                <x-filament::button color="primary" wire:click="confirm">
                    {{ __('Confirm') }}
                </x-filament::button>
                <x-filament::button color="secondary" wire:click="cancel">
                    {{ __('Cancel') }}
                </x-filament::button>
            </x-slot>
        </x-filament::modal>
    @endif
</div>
