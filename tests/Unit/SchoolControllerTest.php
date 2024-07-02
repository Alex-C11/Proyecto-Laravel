<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class SchoolControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAsUser();
    }

    protected function actingAsUser()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user, 'api');
    }

    public function testIndex()
    {
        School::factory()->count(3)->create();

        $response = $this->getJson('/api/auth/schools');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function testShow()
    {
        $school = School::factory()->create();

        $response = $this->getJson("/api/auth/schools/{$school->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $school->id,
                     'name' => $school->name,
                 ]);
    }

    public function testStore()
    {
        $schoolData = [
            'name' => $this->faker->name,
        ];

        $response = $this->postJson('/api/auth/schools', $schoolData);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Registro creado exitosamente!',
                     'school' => [
                         'name' => $schoolData['name'],
                     ],
                 ]);

        $this->assertDatabaseHas('schools', $schoolData);
    }

    public function testUpdate()
    {
        $school = School::factory()->create();

        $updateData = [
            'name' => 'Updated School Name',
        ];

        $response = $this->putJson("/api/auth/schools/{$school->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'record updated successfully!',
                     'school' => [
                         'name' => 'Updated School Name',
                     ],
                 ]);

        $this->assertDatabaseHas('schools', $updateData);
    }

    public function testDestroy()
    {
        $school = School::factory()->create();

        $response = $this->deleteJson("/api/auth/schools/{$school->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Registro eliminado exitosamente!',
                 ]);

        $this->assertDatabaseMissing('schools', ['id' => $school->id]);
    }
}
