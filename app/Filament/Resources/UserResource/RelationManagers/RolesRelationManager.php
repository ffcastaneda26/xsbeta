<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Role;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RolesRelationManager extends RelationManager
{
    protected static string $relationship = 'roles';

    public static function canViewForRecord($ownerRecord, string $pageClass): bool
    {
        // Always visible in 'admin' panel
        if (Filament::getCurrentPanel()->getId() === 'admin') {
            return true;
        }

        // In 'company' panel, visible only if tenant has roles
        if (Filament::getCurrentPanel()->getId() === 'company') {
            $tenant = Filament::getTenant();
            return $tenant && $tenant->roles()->count() > 0;
        }

        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function getTableQuery(): Builder
    {
        $user = $this->getOwnerRecord();
        $query = $user->roles()->getQuery();

        // In admin panel, ensure all attached roles are shown, ignoring tenant scope
        if (Filament::getCurrentPanel()->getId() === 'admin') {
            $query->withoutGlobalScope('tenant');
        }

        return $query;
    }

    public function table(Table $table): Table
    {
        $tenant = Filament::getTenant();
        $panelId = Filament::getCurrentPanel()->getId();
        $user = $this->getOwnerRecord(); // The user being edited

        // Determine header actions based on panel and data availability
        $headerActions = [];

        if ($panelId === 'admin') {
            // Get active companies with roles for the user
            $companies = $user->companies()
                ->whereHas('roles')
                ->where('active', 1)
                ->pluck('companies.name', 'companies.id')
                ->toArray();

            if (count($companies) > 0) {
                $headerActions[] = Tables\Actions\AttachAction::make()
                    ->form(function () use ($companies) {
                        return [
                            Forms\Components\Select::make('company_id')
                                ->label(__('Company'))
                                ->options($companies)
                                ->default(count($companies) === 1 ? key($companies) : null)
                                ->required()
                                ->reactive()
                                ->searchable(),
                            Forms\Components\Select::make('recordId')
                                ->label(__('Role'))
                                ->options(function (callable $get) {
                                    $companyId = $get('company_id');
                                    if (!$companyId) {
                                        return [];
                                    }
                                    return Role::where('company_id', $companyId)
                                        ->pluck('name', 'id')
                                        ->toArray();
                                })
                                ->required()
                                ->searchable(),
                        ];
                    })
                    ->action(function (array $data) {
                        // Get the owner record (the user) explicitly
                        $user = $this->getOwnerRecord();
                        $roleId = $data['recordId'];
                        $companyId = $data['company_id'];
                        // Attach the role with company_id as a pivot attribute
                        $user->roles()->attach($roleId, ['company_id' => $companyId]);
                        static::roleAssigned();
                    })
                    ->preloadRecordSelect();
            } else {
                $headerActions[] = Tables\Actions\Action::make('no_roles')
                    ->label(__('No hay roles que asociar'))
                    ->disabled();
            }
        } elseif ($panelId === 'company' && $tenant) {
            // Get roles for the current tenant
            $roles = Role::where('company_id', $tenant->id)
                ->pluck('name', 'id')
                ->toArray();

            if (count($roles) > 0) {
                $headerActions[] = Tables\Actions\AttachAction::make()
                    ->recordSelect(function () use ($roles) {
                        return Forms\Components\Select::make('recordId')
                            ->label(__('Role'))
                           ->options($roles) // Corregir: pasar $roles directamente, no como [$roles]
                            ->required()
                            ->searchable();
                    })
                    ->action(function (array $data) {
                        // Get the owner record (the user) explicitly
                        $user = $this->getOwnerRecord();
                        $roleId = $data['recordId'];
                        $tenant = Filament::getTenant();
                        // Attach the role with tenant's company_id as a pivot attribute, bypassing tenant scope
                        $user->roles()->attach($roleId, [
                            'company_id' => $tenant->id,
                            'model_type' => User::class, // Especificar el model_type
                        ]);
                        static::roleAssigned();
                    })
                    ->preloadRecordSelect();
            } else {
                $headerActions[] = Tables\Actions\Action::make('no_roles')
                    ->label(__('No hay roles que asociar en la empresa'))
                    ->disabled();
            }
        }

        return $table
            ->heading(__('Roles'))
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Role')),
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('Company')),
            ])
            ->filters([
                //
            ])
            ->headerActions($headerActions)
            ->actions([
                Tables\Actions\DetachAction::make()->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    protected static function roleAssigned()
    {
        \Filament\Notifications\Notification::make()
            ->title(__('Role successfully linked'))
            ->success()
            ->send();
    }
}
