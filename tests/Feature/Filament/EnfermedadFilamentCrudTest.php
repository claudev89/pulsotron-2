<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\EnfermedadResource\Pages\CreateEnfermedad;
use App\Filament\Resources\EnfermedadResource\Pages\ListEnfermedads;
use App\Models\Enfermedad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EnfermedadFilamentCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_list_enfermedades_page_loads_for_authenticated_user(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ListEnfermedads::class)
            ->assertSuccessful();
    }

    public function test_create_enfermedad_persists_data(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CreateEnfermedad::class)
            ->fillForm([
                'nombre' => 'Hipertensión',
                'descripcion' => 'Presión arterial elevada crónica',
            ])
            ->call('create')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('enfermedads', [
            'nombre' => 'Hipertensión',
            'descripcion' => 'Presión arterial elevada crónica',
        ]);
    }

    public function test_list_page_shows_existing_enfermedades(): void
    {
        $this->actingAs($this->admin);

        Enfermedad::factory()->create(['nombre' => 'Asma']);
        Enfermedad::factory()->create(['nombre' => 'Diabetes']);

        Livewire::test(ListEnfermedads::class)
            ->assertSuccessful()
            ->assertSee('Asma')
            ->assertSee('Diabetes');
    }

    public function test_enfermedad_can_be_updated_via_model(): void
    {
        $enfermedad = Enfermedad::factory()->create(['nombre' => 'Original']);

        $enfermedad->update(['nombre' => 'Actualizado']);

        $this->assertDatabaseHas('enfermedads', [
            'id' => $enfermedad->id,
            'nombre' => 'Actualizado',
        ]);
    }

    public function test_enfermedad_can_be_deleted_via_model(): void
    {
        $enfermedad = Enfermedad::factory()->create();

        $enfermedad->delete();

        $this->assertDatabaseMissing('enfermedads', ['id' => $enfermedad->id]);
    }


}
