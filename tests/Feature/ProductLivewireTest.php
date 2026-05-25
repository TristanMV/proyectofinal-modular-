<?php

use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Livewire\Actions\ProductManager;
use Livewire\Livewire;

test('authenticated user can see dashboard page', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get('/dashboard');
    
    $response->assertStatus(200);
});

test('can create product via livewire component', function () {
    $user = User::factory()->create();
    $store = Store::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(ProductManager::class)
        ->set('name', 'Módulo GPS Avanzado')
        ->set('description', 'Dispositivo con antena externa')
        ->set('price', 850.00)
        ->call('store');

    $this->assertDatabaseHas('products', ['name' => 'Módulo GPS Avanzado']);
});

test('validation fails if price is missing', function () {
    $user = User::factory()->create();
    $store = Store::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(ProductManager::class)
        ->set('name', 'Invalido')
        ->set('price', '')
        ->call('store')
        ->assertHasErrors(['price' => 'required']);
});

test('can soft delete product via livewire', function () {
    $user = User::factory()->create();
    $store = Store::factory()->create(['user_id' => $user->id]);
    $product = Product::factory()->create(['store_id' => $store->id]);

    Livewire::actingAs($user)
        ->test(ProductManager::class)
        ->call('destroy', $product->id);

    $this->assertSoftDeleted($product);
});