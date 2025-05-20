<?php

namespace App\Enums\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Facades\App;

enum VoucherDocumentTypeEnum: string implements HasLabel,HasColor,HasIcon
{
    case Opening = 'Opening';
    case Outflow = 'Outflow';
    case Inflow = 'Inflow';
    case Transfer = 'Transfer';
    public function getLabel(): ?string
    {
        if (App::isLocale('en')) {
            return match ($this) {
                self::Opening => 'Opening',
                self::Outflow => 'Outflow',
                self::Inflow => 'Inflow',
                self::Transfer => 'Transfer',
            };
        }
        return match ($this) {
            self::Opening => 'Apertura',
            self::Outflow => 'Egreso',
            self::Inflow => 'Ingreso',
            self::Transfer => 'Traspaso',
        };

    }

    public function getColor(): array|string|null
    {
        return match ($this) {

            self::Opening => 'danger',
            self::Outflow => 'red',
            self::Inflow => 'success',
            self::Transfer => 'green'

        };
    }
    public function getIcon(): ?string
    {
        return match ($this) {
            self::Opening => 'heroicon-m-bell-alert',
            self::Outflow => 'heroicon-m-arrow-left',
            self::Inflow => 'heroicon-m-arrow-long-right',
            self::Transfer => 'heroicon-m-arrows-right-left',

        };
    }

}
