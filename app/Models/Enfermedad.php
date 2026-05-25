<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enfermedad extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function sintomas(): HasMany
    {
        return $this->hasMany(Sintoma::class);
    }

    public function diagnosticos(): BelongsToMany
    {
        return $this->belongsToMany(Diagnostico::class);
    }

    public function pacientes(): BelongsToMany
    {
        return $this->belongsToMany(Paciente::class, 'enfermedads_pacientes', 'enfermedad_id', 'paciente_rut');
    }
}
