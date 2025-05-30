<x-dynamic-component :component="$getFieldWrapperView()" :id="$getId()" :label="$getLabel()" :label-sr-only="$isLabelHidden()" :helper-text="$getHelperText()"
    :hint="$getHint()" :hint-icon="$getHintIcon()" :required="$isRequired()" :state-path="$getStatePath()">
    <div class="table-repeater">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-800">
                    <th class="px-4 py-2 text-left">{{ __('Account') }}</th>
                    <th class="px-4 py-2 text-left">{{ __('Description') }}</th>
                    <th class="px-4 py-2 text-right">{{ __('Debit') }}</th>
                    <th class="px-4 py-2 text-right">{{ __('Credit') }}</th>
                    <th class="px-4 py-2"></th> <!-- For repeater actions -->
                </tr>
            </thead>
            <tbody>
                @foreach ($getState() as $uuid => $item)
                    <tr wire:key="{{ $this->getStatePath() }}.{{ $uuid }}">
                        <td class="px-4 py-2">
                            <x-filament::input.wrapper>
                                {{ $getChildComponent('accounting_account_id', $uuid) }}
                            </x-filament::input.wrapper>
                        </td>
                        <td class="px-4 py-2">
                            <x-filament::input.wrapper>
                                {{ $getChildComponent('glosa', $uuid) }}
                            </x-filament::input.wrapper>
                        </td>
                        <td class="px-4 py-2">
                            <x-filament::input.wrapper>
                                {{ $getChildComponent('debit', $uuid) }}
                            </x-filament::input.wrapper>
                        </td>
                        <td class="px-4 py-2">
                            <x-filament::input.wrapper>
                                {{ $getChildComponent('credit', $uuid) }}
                            </x-filament::input.wrapper>
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex items-center space-x-2">
                                @if ($canDeleteItem($uuid))
                                    <button type="button" wire:click="removeItem('{{ $uuid }}')"
                                        class="text-danger-600 hover:text-danger-800">
                                        <x-filament::icon icon="heroicon-o-trash" class="w-5 h-5" />
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            <button type="button" wire:click="addItem" class="text-primary-600 hover:text-primary-800">
                {{ __('Add Movement') }}
            </button>
        </div>
    </div>
</x-dynamic-component>
