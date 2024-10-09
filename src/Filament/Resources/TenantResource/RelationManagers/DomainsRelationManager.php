<?php

namespace TomatoPHP\FilamentTenancy\Filament\Resources\TenantResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DomainsRelationManager extends RelationManager
{
    protected static string $relationship = 'domains';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return trans('filament-tenancy::messages.domains.title');
    }

    protected static function getModelLabel(): ?string
    {
        return trans('filament-tenancy::messages.domains.single'); // TODO: Change the autogenerated stub
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('domain')
                    ->required()
                    ->label(trans('filament-tenancy::messages.domains.columns.domain'))
                    ->prefix(request()->getScheme()."://")
                    ->suffix(".".request()->getHost())
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('domain')
            ->columns([
                Tables\Columns\TextColumn::make('domain')
                    ->label(trans('filament-tenancy::messages.domains.columns.domain')),
                Tables\Columns\TextColumn::make('full-domain')
                    ->label(trans('filament-tenancy::messages.domains.columns.full'))
                    ->getStateUsing(fn($record) => \Str::of($record->domain)->append('.')->append(request()->getHost()))
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
