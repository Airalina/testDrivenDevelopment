<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RepositoryControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     

    public function test_invitado()
    {
        $this->withoutExceptionHandling(); 
        $this->withoutMiddleware();
         #1. que quiero que suceda cuando visite la raiz
        $this->get('repositories')->assertStatus(200); #index
        $this->get('repositories/create')->assertStatus(200); #create
        $this->get('repositories/1')->assertStatus(200); #show
        $this->get('repositories/1/edit')->assertStatus(200); #edit
       $this->post('repositories', [])->assertStatus(200); #store
       $this->put('repositories/1')->assertStatus(200); #update
       $this->delete('repositories/1')->assertStatus(200); #delete

    }
     */

     public function test_index_empty()
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create();

        $this->actingAs($user)
            ->get('repositories')
            ->assertStatus(500)
            ->assertSee('No hay repositorios creados');
    }

    public function test_index_me()
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get('repositories')
            ->assertSee($repository->id);
    }

    public function test_store()
    {
        $user = User::factory()->create();

        $this->withoutExceptionHandling();
        $this->withoutMiddleware();
        $data = [
            'url' => $this->faker->url,
            'description' => $this->faker->text
        ];

        $this->actingAs($user)
            ->post('repositories', $data)
            ->assertRedirect('repositories');

        $this->assertDatabaseHas('repositories', $data);
    }

    public function test_update()
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create(['user_id' => $user->id]);

        $this->withoutExceptionHandling();
        $this->withoutMiddleware();
        $data = [
            'url' => $this->faker->url,
            'description' => $this->faker->text
        ];

        $this->actingAs($user)
            ->put("repositories/$repository->id", $data)
            ->assertRedirect('repositories');

        $this->assertDatabaseHas('repositories', $data);
    }

    public function test_validation_store()
    {
        $user = User::factory()->create();

        $this->withoutMiddleware();

        $this->actingAs($user)
            ->post('repositories', [])
            ->assertStatus(302)
            ->assertSessionHasErrors(['url', 'description']);
    }

    public function test_validation_update()
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create();

        $this->withoutMiddleware();

        $this->actingAs($user)
            ->put("repositories/$repository->id", [])
            ->assertStatus(302)
            ->assertSessionHasErrors(['url', 'description']);
    }

    public function test_update_policy()
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create();

    
        $data = [
            'url' => $this->faker->url,
            'description' => $this->faker->text
        ];

        $this->actingAs($user)
            ->put("repositories/$repository->id", $data)
            ->assertStatus(419);
    }

    public function test_delete()
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create(['user_id' => $user->id]);

        $this->withoutExceptionHandling();
        $this->withoutMiddleware();

        $this->actingAs($user)
            ->delete("repositories/$repository->id")
            ->assertRedirect('repositories');

        $this->assertDatabaseMissing('repositories', [
            'id' => $repository->id,
            'url' => $repository->url,
            'description' => $repository->description
        ]);
    }

    public function test_delete_policy()
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create();

        $this->actingAs($user)
            ->delete("repositories/$repository->id")
            ->assertStatus(419);

        $this->assertDatabaseMissing('repositories', [
            'id' => $repository->id,
            'user_id' => $repository->user_id,
            'created_at' => $repository->created_at,
            'updated_at' => $repository->updated_at,
            'url' => $repository->url,
            'description' => $repository->description
        ]);
    }

    public function test_show()
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create(['user_id' => $user->id]);

        $this->withoutExceptionHandling();
        $this->withoutMiddleware();

        $this->actingAs($user)
            ->get("repositories/$repository->id")
            ->assertStatus(200);

    }

    public function test_edit()
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create(['user_id' => $user->id]);

        $this->withoutExceptionHandling();
        $this->withoutMiddleware();

        $this->actingAs($user)
            ->get("repositories/$repository->id")
            ->assertStatus(200);
    }

    public function test_create()
    {
        $user = User::factory()->create();
        $this->withoutExceptionHandling();
        $this
        ->actingAs($user)
        ->get('repositories/create')
        ->assertStatus(200);
    } 

}
