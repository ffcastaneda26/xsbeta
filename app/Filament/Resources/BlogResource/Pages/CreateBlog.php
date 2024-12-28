<?php

namespace App\Filament\Resources\BlogResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use App\Filament\Resources\BlogResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateBlog extends CreateRecord
{
    protected static string $resource = BlogResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = Str::slug($data['title']);
        if(!Auth::user()->hasRole('Administrador') && !Auth::user()->hasRole('Super Admin')){
            $data['author_id'] = Auth::user()->id;
        }
        return $data;
    }

}
