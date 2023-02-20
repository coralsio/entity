<?php

namespace Tests\Feature;

use Corals\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class EntitiesViewTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $user = User::query()->whereHas('roles', function ($query) {
            $query->where('name', 'superuser');
        })->first();
        Auth::loginUsingId($user->id);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_entities_view()
    {
        $response = $this->get('entity/entities');

        $response->assertStatus(200)->assertViewIs('Entity::entities.index');
    }

    public function test_entities_create()
    {
        $response = $this->get('entity/entities/create');

        $response->assertViewIs('Entity::entities.create_edit')->assertStatus(200);
    }
}
