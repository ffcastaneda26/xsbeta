<?php
$segments = explode('-', $value);
$lastSegment = end($segments);

// Rule: If last segment is 000, it must be a parent account (no parent_id)
if ($lastSegment === '000' && request()->input('parent_id')) {
$fail(__('Parent accounts (last segment 000) cannot have a parent'));
\Filament\Notifications\Notification::make()
->title(__('Parent accounts cannot have a parent'))
->danger()
->send();
return;
}

// Rule: If a left segment is 000, the right segment must be non-zero
for ($i = 0; $i < count($segments) - 1; $i++) { if ($i==0) { if ($segments[$i]===str_repeat('0', strlen($segments[$i])))
    { $fail(__('The first segment cannot be all zeros')); \Filament\Notifications\Notification::make() ->
    title(__('Account structure is incorrect'))
    ->danger()
    ->send();
    return;
    }
    }

    if ($i > 0) {

    if($segments[$i-1] === str_repeat('0',strlen($segments[$i]))){
    dd('El segmento anterior son ceros');
    }



    }
    // if ($segments[$i] === str_repeat('0', strlen($segments[$i])) && $segments[$i + 1] === str_repeat('0',
    strlen($segments[$i + 1]))) {
    // $fail(__('If a segment is all zeros, the next segment must be non-zero'));
    // \Filament\Notifications\Notification::make()
    // ->title(__('Invalid segment structure'))
    // ->danger()
    // ->send();
    // return;
    // }
    }

    // Validate parent-child code consistency
    $parentId = request()->input('parent_id');
    if ($parentId) {
    $parent = AccountingAccount::find($parentId);
    if ($parent) {
    $parentSegments = explode('-', $parent->code);
    $childSegments = $segments;
    $parentLevel = count($parentSegments) - 1;

    for ($i = 0; $i < $parentLevel; $i++) { if ($parentSegments[$i] !==$childSegments[$i]) { $fail(__('Child account
        code must match parent code up to parent level')); \Filament\Notifications\Notification::make() ->
        title(__('Invalid parent-child code structure'))
        ->danger()
        ->send();
        return;
        }
        }
        }
        }
