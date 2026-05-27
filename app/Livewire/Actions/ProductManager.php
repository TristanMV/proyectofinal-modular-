<?php

namespace App\Livewire\Actions;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductManager extends Component
{
    use WithFileUploads;

    // Propiedades del formulario
    public $name, $description, $price, $image;
    
    // Reglas de validación (Rúbrica: Validación en Servidor)
    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image|max:2048',
    ];

    public function store()
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }

        // Le decimos explícitamente a Intelephense que este es nuestro Modelo User
        /** @var \App\Models\User $user */
        $user = \Illuminate\Support\Facades\Auth::user();
        $store = $user->stores()->first();

        $store->products()->create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'image_path' => $imagePath,
        ]);

        $this->reset(['name', 'description', 'price', 'image']);

        // Usamos el Facade Session para eliminar la línea roja de VS Code
        \Illuminate\Support\Facades\Session::flash('success', '¡Producto añadido exitosamente a GeoStore!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Autorización: Usamos auth()->id() directo para evitar quejas del editor
        if (Auth::id() === $product->store->user_id) {
            $product->delete(); 
        }
    }

    public function render()
    {
        $products = \App\Models\Product::with('categories')->get();

        // Como la carpeta livewire ya está suelta en views, esta ruta ahora sí funcionará
        return view('livewire.product-manager', compact('products'));
    }
}