<?php

namespace Tests\Feature;

use App\Models\Comuna;
use App\Models\Enfermedad;
use App\Models\Paciente;
use App\Models\Sintoma;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PacienteModelTest extends TestCase
{
    use RefreshDatabase;

    private Paciente $paciente;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('regions')->insert(['id' => 1, 'nombre' => 'Región de prueba']);
        DB::table('comunas')->insert(['id' => 1, 'nombre' => 'Comuna de prueba', 'region_id' => 1]);

        $this->paciente = Paciente::create([
            'rut' => '11111111-1',
            'nombre' => 'Paciente Test',
            'fecha_nacimiento' => '1990-01-01',
            'direccion_calle' => 'Av. Prueba',
            'direccion_numero' => 123,
            'comuna_id' => 1,
            'celular' => 98765432,
        ]);
    }

    public function test_paciente_belongs_to_comuna(): void
    {
        $this->assertNotNull($this->paciente->comuna);
        $this->assertEquals('Comuna de prueba', $this->paciente->comuna->nombre);
        $this->assertEquals(1, $this->paciente->comuna->region_id);
    }

    public function test_motivo_consulta_accessors_return_empty_by_default(): void
    {
        $this->assertEquals('', $this->paciente->motivo_consulta_1);
        $this->assertEquals('', $this->paciente->motivo_consulta_2);
        $this->assertEquals('', $this->paciente->motivo_consulta_3);
    }

    public function test_motivo_consulta_accessors_return_stored_values(): void
    {
        DB::table('motivo_consulta')->insert([
            ['paciente_rut' => '11111111-1', 'prioridad' => 1, 'descripcion' => 'Control mensual'],
            ['paciente_rut' => '11111111-1', 'prioridad' => 2, 'descripcion' => 'Dolor lumbar'],
        ]);

        $paciente = $this->paciente->fresh();

        $this->assertEquals('Control mensual', $paciente->motivo_consulta_1);
        $this->assertEquals('Dolor lumbar', $paciente->motivo_consulta_2);
        $this->assertEquals('', $paciente->motivo_consulta_3);
    }

    public function test_paciente_belongs_to_many_sintomas_previos(): void
    {
        $sintoma = Sintoma::factory()->create();

        DB::table('paciente_sintomas_previos')->insert([
            'paciente_rut' => $this->paciente->rut,
            'sintoma_id' => $sintoma->id,
            'frecuencia' => 'frecuente',
        ]);

        $this->assertCount(1, $this->paciente->sintomasPrevios);
        $this->assertTrue($this->paciente->sintomasPrevios->contains($sintoma));
        $this->assertEquals('frecuente', $this->paciente->sintomasPrevios->first()->pivot->frecuencia);
    }

    public function test_paciente_belongs_to_many_enfermedades(): void
    {
        $enfermedad = Enfermedad::factory()->create();

        DB::table('enfermedads_pacientes')->insert([
            'enfermedad_id' => $enfermedad->id,
            'paciente_rut' => $this->paciente->rut,
        ]);

        $this->assertCount(1, $this->paciente->enfermedades);
        $this->assertTrue($this->paciente->enfermedades->contains($enfermedad));
    }

    public function test_paciente_has_correct_primary_key(): void
    {
        $this->assertEquals('rut', $this->paciente->getKeyName());
        $this->assertFalse($this->paciente->incrementing);
        $this->assertEquals('string', $this->paciente->getKeyType());
    }

    public function test_paciente_can_be_retrieved_by_rut(): void
    {
        $found = Paciente::find('11111111-1');
        $this->assertNotNull($found);
        $this->assertEquals('Paciente Test', $found->nombre);
    }
}
