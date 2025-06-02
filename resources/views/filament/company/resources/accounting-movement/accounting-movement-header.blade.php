<div class="grid grid-cols-3 gap-4 p-4">

    <div class="flex justify-between">

        <label class="block text-sm font-medium text-gray-700">
            {{ __('Total Debit') }} {{ $debitTotal }}

        </label>

        <label class="block text-sm font-medium text-gray-700">
            {{ __('Total Credit') }} {{ $creditTotal }}
        </label>
        <div class="bg-red-600 text-white font-bold text-sm p-2 rounded">

            <label class="mt-1">{{ $isUnbalanced ? __('Checked') :  __('Unbalanced') }} </label>
        </div>


    </div>


</div>
