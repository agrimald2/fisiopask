<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

use Inertia\Testing\Assert;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;

it('doctors get index', function () {
    $this->actingAs($user = User::factory()->create());

    $user->assignRole(Role::create(['name' => 'admin']));

    getJson('/dashboard/doctors')
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
                ->has('model')
        );
});
