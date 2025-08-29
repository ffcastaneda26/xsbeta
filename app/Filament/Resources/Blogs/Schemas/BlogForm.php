<?php

namespace App\Filament\Resources\Blogs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Illuminate\Http\UploadedFile;

class BlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()->schema([
                    Select::make('author_id')
                        ->relationship('author', 'name')
                        ->label('Autor')
                        ->required(),
                    TextInput::make('title')
                        ->label('Título')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

                    TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->readOnly(),
                    Section::make()->schema([
                        Toggle::make('is_published')
                            ->label('¿Publicado?')
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->label('Fecha de Publicación')
                            ->requiredIf('is_published', true)
                            ->validationMessages([
                                'requiredIf' => 'La fecha de publicación es obligatoria cuando el contenido está marcado como publicado.'
                            ]),
                    ])->columns(2),

                    Section::make()->schema([
                        FileUpload::make('image')
                            ->label('-Imagen')
                            ->image()
                            ->directory('blogs')
                            ->getUploadedFileNameForStorageUsing(
                                fn(UploadedFile $file): string => time() . '_' . $file->getClientOriginalName(),
                            ),
                    ])->columnSpanFull(),

                ]),
                Group::make()->schema([
                    RichEditor::make('description')
                        ->label('Descripción')
                        ->columnSpanFull(),

                    RichEditor::make('content')
                        ->label('Contenido')
                        ->columnSpanFull()
                        ->extraAttributes([
                            'style' => 'height: 200px; overflow-y: auto;',
                        ]),
                ]),



            ]);
    }
}
