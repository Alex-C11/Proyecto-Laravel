<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Place;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlaceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        Place::factory()->count(3)->create();

        $response = $this->getJson('/api/auth/places');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function testShow()
    {
        $place = Place::factory()->create();

        $response = $this->getJson("/api/auth/places/{$place->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $place->id,
                     'name' => $place->name,
                     'address' => $place->address,
                 ]);
    }

    public function testStore()
    {
        $placeData = [
            'name' => 'New Place',
            'address' => 'Description of the new place',
        ];

        $response = $this->postJson('/api/auth/places', $placeData);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Registro creado exitosamente!',
                     'place' => [
                         'name' => 'New Place',
                         'address' => 'Description of the new place',
                     ],
                 ]);

        $this->assertDatabaseHas('places', $placeData);
    }

    public function testUpdate()
    {
        $place = Place::factory()->create();

        $updateData = [
            'name' => 'Updated Place',
            'address' => 'Updated address',
        ];

        $response = $this->putJson("/api/auth/places/{$place->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'record updated successfully!',
                     'place' => [
                         'name' => 'Updated Place',
                         'address' => 'Updated address',
                     ],
                 ]);

        $this->assertDatabaseHas('places', $updateData);
    }

    public function testDestroy()
    {
        $place = Place::factory()->create();

        $response = $this->deleteJson("/api/auth/places/{$place->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Registro eliminado exitosamente!',
                 ]);

        $this->assertDatabaseMissing('places', ['id' => $place->id]);
    }
}
