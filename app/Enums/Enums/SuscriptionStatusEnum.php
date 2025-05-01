<?php

namespace App\Enums\Enums;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Illuminate\Support\Facades\App;
use Filament\Support\Contracts\HasLabel;


enum SuscriptionStatusEnum:string implements HasLabel,HasColor,HasIcon
{
    case Valid = 'Valid';

    case Expired = 'Expired';

    public function getLabel(): ?string
    {
        if(App::isLocale('en')){
            return match ($this) {
                self::Valid => 'Valid',
                self::Expired=> 'Expired',
            };
        }
        return match ($this) {
            self::Valid => 'Vigente',
            self::Expired=> 'Vencido'
        };

    }

    public function getColor(): array|string|null
    {
        return match ($this) {

            self::Valid => 'success',
            self::Expired=> 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Valid => 'heroicon-m-check-circle',
            self::Expired=> 'heroicon-m-x-circle',
        };
    }

    public function getFontAwasomeIcon(): ?string
    {
        return match ($this) {
            self::Valid => 'fa-solid fa-circle-check',
            self::Expired=> 'fa-solid fa-circle-xmark',
        };
    }
}
