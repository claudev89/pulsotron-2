<?php

namespace App\Filament\Resources\EnfermedadResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PacienteRelationManager extends RelationManager
{
    protected static string $relationship = 'pacientes';

    protected static ?string $inverseRelationship = 'enfermedades';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('rut')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                Tables\Columns\TextColumn::make('rut')->label('RUT')->searchable(),
                Tables\Columns\TextColumn::make('nombre')->label('Nombre')->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                
            ]);
    }
}
