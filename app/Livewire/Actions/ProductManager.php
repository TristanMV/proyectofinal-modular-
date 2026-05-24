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

        // Buscamos la tienda asignada al usuario logueado mediante el Modelo
        $store = \App\Models\User::find(Auth::id())->stores()->first();
        
        if (!$store) {
            session()->flash('error', 'No tienes una tienda asignada para crear productos.');
            return;
        }

        $store->products()->create([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'image_path' => $imagePath,
        ]);

        $this->reset(['name', 'description', 'price', 'image']);
        session()->flash('success', '¡Producto añadido exitosamente a GeoStore!');
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
        // EAGER LOADING
        $products = Product::with('categories')->get();

        // Cambiamos la ruta de la vista a la de components
        return view('components.livewire.product-manager', compact('products'));
    }
}