<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Facades\App;

enum VoucherStatusEnum:string implements HasLabel,HasColor,HasIcon
{
    case INVALID = 'invalido';
    case CURRENT = 'Current';
    case PENDING = 'Pending';
    case FINISHED = 'Finished';



    public function getLabel(): ?string
    {
        if (App::isLocale('en')) {
            return match ($this) {
                self::INVALID => 'Invalid',
                self::CURRENT => 'Current',
                self::PENDING => 'Pending',
                self::FINISHED => 'Finished',
            };
        }
        return match ($this) {
            self::INVALID => 'Descuadrado',
            self::CURRENT => 'Vigente',
            self::PENDING => 'Pendiente',
            self::FINISHED => 'Finalizado',
        };

    }

    public function getColor(): array|string|null
    {
        return match ($this) {
            self::INVALID => 'Danger',
            self::CURRENT => 'Primary',
            self::PENDING => 'Warning',
            self::FINISHED => 'Success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::INVALID => 'heroicon-m-bell-alert',
            self::CURRENT => 'heroicon-m-check',
            self::PENDING => 'heroicon-m-check-badge',
            self::FINISHED => 'heroicon-m-scale',
        };
    }
}
