<div class="flex justify-between items-center">
    @if ($unbalancedMessage)
        <div class="text-white bg-white p-2">
            {{ $unbalancedMessage }}
        </div>
    @else
        <div></div>
    @endif
    <div>
        {{ $createAction }}
    </div>
</div>
