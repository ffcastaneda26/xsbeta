<div>
    <x-filament::button wire:click="openModal">Create Voucher</x-filament::button>

    <x-filament::modal :open="$showModal" wire:model="showModal">
        <x-filament::modal.heading>Create New Voucher</x-filament::modal.heading>

        <form wire:submit.prevent="createVoucher">
            <div class="space-y-4">
                <x-filament::input.wrapper>
                    <x-filament::input.radio
                        wire:model="type"
                        label="Voucher Type"
                        :options="\App\Enums\VoucherTypeEnum::class"
                        inline
                        required
                    />
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input.select
                        wire:model="document_type"
                        label="Document Type"
                        :options="\App\Enums\VoucherDocumentTypeEnum::class"
                        required
                    />
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input.date
                        wire:model="date"
                        label="Date"
                        format="d-m-Y"
                        required
                    />
                </x-filament::input.wrapper>

                <x-filament::input.wrapper>
                    <x-filament::input.textarea
                        wire:model="glosa"
                        label="Description"
                        required
                    />
                </x-filament::input.wrapper>

                <div class="flex justify-end space-x-2">
                    <x-filament::button type="submit">Create</x-filament::button>
                    <x-filament::button wire:click="$set('showModal', false)">Cancel</x-filament::button>
                </div>
            </div>
        </form>
    </x-filament::modal>
</div>
