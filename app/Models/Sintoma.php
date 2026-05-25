<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sintoma extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function enfermedad(): BelongsToMany
    {
        return $this->belongsToMany(Enfermedad::class);
    }

    public function pacientes()
    {
        return $this->belongsToMany(Paciente::class, 'paciente_sintomas_previos', 'sintoma_id', 'paciente_rut');
    }
}
