<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpleadosResource\Pages;
use App\Models\Empleados;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;

class EmpleadosResource extends Resource
{
    protected static ?string $model = Empleados::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información Personal')
                    ->schema([
                        TextInput::make('primer_nombre')->required(),
                        TextInput::make('segundo_nombre'),
                        TextInput::make('primer_apellido')->required(),
                        TextInput::make('segundo_apellido'),
                        Select::make('tipo_identificacion')
                            ->options([
                                'CC' => 'Cédula de Ciudadanía',
                                'CE' => 'Cédula de Extranjería',
                                'TI' => 'Tarjeta de Identidad',
                                // ...otros tipos de identificación si aplican
                            ])->required(),
                        TextInput::make('numero_identificacion')->required(),
                        TextInput::make('telefono'),
                        TextInput::make('correo_electronico')->email(),
                        TextInput::make('direccion'),
                        TextInput::make('ciudad'),
                        TextInput::make('departamento'),
                    ])->columns(2),

                Forms\Components\Section::make('Información Laboral')
                    ->schema([
                        TextInput::make('cargo')->required(),
                        DatePicker::make('fecha_ingreso')->required(),
                        DatePicker::make('fecha_retiro')->nullable(),
                        TextInput::make('salario_basico')->numeric()->required(),
                        Select::make('tipo_contrato')
                            ->options([
                                'indefinido' => 'Indefinido',
                                'fijo' => 'Fijo',
                                'obra_labor' => 'Obra o Labor',
                                // ...otros tipos de contrato si aplican
                            ])->required(),
                        TextInput::make('codigo_trabajador'),
                        Select::make('tipo_cotizante')
                            ->options([
                                'dependiente' => 'Dependiente',
                                'independiente' => 'Independiente',
                                // ...otros tipos de cotizante si aplican
                            ])->required(),
                        TextInput::make('subtipo_cotizante')->numeric(), // Ejemplo: 00, 01, etc.
                        Toggle::make('pensionado'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero_identificacion')->searchable(),
                TextColumn::make('primer_nombre')->searchable(),
                TextColumn::make('primer_apellido')->searchable(),
                TextColumn::make('cargo'),
            ])
            ->filters([
                // Puedes agregar filtros aquí si es necesario
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Relaciones con otros recursos si las tienes
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmpleados::route('/'),
            'create' => Pages\CreateEmpleados::route('/create'),
            'edit' => Pages\EditEmpleados::route('/{record}/edit'),
        ];
    }
}
