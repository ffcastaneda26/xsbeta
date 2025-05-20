<?php

namespace App\Enums\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Facades\App;

enum VoucherTypeEnum:string implements HasLabel,HasColor,HasIcon
{

    case Both = 'Both';
    case IFRS = 'IFRS';
    case TAX = "Tax";



    public function getLabel(): ?string
    {
        if (App::isLocale('en')) {
            return match ($this) {
                self::Both => 'Both',
                self::IFRS => 'IFRS',
                self::TAX => 'TAX',


            };
        }
        return match ($this) {
            self::Both => 'Ambos',
            self::IFRS => 'IFRS',
            self::TAX => 'Tributario',

        };

    }

    public function getColor(): array|string|null
    {
        return match ($this) {

            self::Both => 'success',
            self::IFRS => 'danger',
            self::TAX => 'primary'
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Both => 'heroicon-m-bell-alert',
            self::IFRS => 'heroicon-m-check',
            self::TAX => 'heroicon-m-calendar',

        };
    }

    public function getFontAwasomeIcon(): ?string
    {
        return match ($this) {
            self::Both => 'fa-regular fa-bell',
            self::IFRS => 'fa-solid fa-check-circle',
            self::TAX => 'fa-solid fa-check-circle',
        };
    }

}
