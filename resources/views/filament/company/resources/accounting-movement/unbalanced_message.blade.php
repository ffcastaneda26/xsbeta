<div style="text-align: center;">
    @if ($error)
        <div
            style="background-color: red; color: white; font-weight: bold; font-size: 0.8rem; text-align: center; display: inline-block; padding: 0.5rem 1rem;">
            {{ $message }}
        </div>
    @else
        <div
            style="background-color: rgb(0, 255, 60); color: white; font-weight: bold; font-size: 0.8rem; text-align: center; display: inline-block; padding: 0.5rem 1rem;">
            {{ $message }}
        </div>
    @endif
</div>
