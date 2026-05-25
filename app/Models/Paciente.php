<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Paciente extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'rut';
    public $incrementing = false;
    protected $keyType = 'string';

    public function comuna(): BelongsTo
    {
        return $this->belongsTo(Comuna::class);
    }
    public function actualizarMotivosContulta()
    {
        foreach ([1, 2, 3] as $prioridad) {
            $descripcion = $this->{ 'motivo_consulta_' . $prioridad } ?? null;

            if(!empty($descripcion)) {
                DB::table('motivo_consulta')->updateOrInsert(
                    [
                        'prioridad' => $prioridad,
                        'paciente_rut' => $this->rut
                    ],
                    [
                        'descripcion' => $descripcion
                    ]
                );
            } else
            {
                DB::table('motivo_consulta')
                    ->where('prioridad', $prioridad)
                    ->where('paciente_rut', $this->rut)
                    ->delete();
            }
        }
    }

    public function getMotivoConsulta1Attribute()
    {
        return DB::table('motivo_consulta')
            ->where('paciente_rut', $this->rut)
            ->where('prioridad', 1)
            ->value('descripcion') ?? '';
    }

    public function getMotivoConsulta2Attribute()
    {
        return DB::table('motivo_consulta')
            ->where('paciente_rut', $this->rut)
            ->where('prioridad', 2)
            ->value('descripcion') ?? '';
    }

    public function getMotivoConsulta3Attribute()
    {
        return DB::table('motivo_consulta')
            ->where('paciente_rut', $this->rut)
            ->where('prioridad', 3)
            ->value('descripcion') ?? '';
    }


    public function diagnosticos() : BelongsTo
    {
        return $this->BelongsTo(Diagnostico::class);
    }

    public function sintomasPrevios()
    {
        return $this->belongsToMany(Sintoma::class, 'paciente_sintomas_previos', 'paciente_rut', 'sintoma_id')
            ->withPivot('frecuencia');
    }

    public function medicamentos()
    {
        return $this->belongsToMany(Medicamento::class);
    }

    public function enfermedades(): BelongsToMany
    {
        return $this->belongsToMany(Enfermedad::class, 'enfermedads_pacientes', 'paciente_rut', 'enfermedad_id');
    }
}
