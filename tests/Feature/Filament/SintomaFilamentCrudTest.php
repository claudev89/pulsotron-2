<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\SintomaResource\Pages\ListSintomas;
use App\Models\Sintoma;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SintomaFilamentCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create();
    }

    public function test_list_sintomas_page_loads_for_authenticated_user(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ListSintomas::class)
            ->assertSuccessful();
    }

    public function test_sintoma_can_be_created_via_model(): void
    {
        $sintoma = Sintoma::factory()->create([
            'nombre' => 'Dolor de cabeza',
            'descripcion' => 'Cefalea tensional bilateral',
        ]);

        $this->assertDatabaseHas('sintomas', [
            'id' => $sintoma->id,
            'nombre' => 'Dolor de cabeza',
            'descripcion' => 'Cefalea tensional bilateral',
        ]);
    }

    public function test_sintoma_can_be_updated_via_model(): void
    {
        $sintoma = Sintoma::factory()->create();

        $sintoma->update(['nombre' => 'Dolor abdominal']);

        $this->assertDatabaseHas('sintomas', [
            'id' => $sintoma->id,
            'nombre' => 'Dolor abdominal',
        ]);
    }

    public function test_sintoma_can_be_deleted_via_model(): void
    {
        $sintoma = Sintoma::factory()->create();

        $sintoma->delete();

        $this->assertDatabaseMissing('sintomas', ['id' => $sintoma->id]);
    }

    public function test_list_page_shows_existing_sintomas(): void
    {
        $this->actingAs($this->admin);

        Sintoma::factory()->create(['nombre' => 'Mareos']);
        Sintoma::factory()->create(['nombre' => 'Fatiga']);

        Livewire::test(ListSintomas::class)
            ->assertSuccessful()
            ->assertSee('Mareos')
            ->assertSee('Fatiga');
    }
}
