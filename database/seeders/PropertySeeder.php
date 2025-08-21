<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run()
    {
        // Crée 20 propriétés factices
        Property::factory()->count(20)->create();
        
        // Optionnel : ajoutez quelques propriétés spécifiques en plus
        Property::factory()->create([
            'title' => 'Appartement exceptionnel avec vue sur mer',
            'city' => 'Marseille',
            'price' => 350000,
            'surface' => 85,
            'sold' => false,
        ]);
        
        Property::factory()->create([
            'title' => 'Maison de caractère en centre-ville',
            'city' => 'Paris',
            'price' => 750000,
            'surface' => 120,
            'sold' => true,
        ]);
    }
}