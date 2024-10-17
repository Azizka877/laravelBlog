<?php

namespace Tests\Feature;

use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PropertyTest extends TestCase
{
   use RefreshDatabase;
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_ok_on_contact(): void
    {
        /**
         * @var Property $property
         */
        $property = Property::factory()->create();

        
        $response = $this->post("/biens/{$property->id}/contact",[
            'firstname' => 'John',
            'lastname' => 'Doe',
            
            'email' => 'johndoe@example.com',
            'phone' => '1234567890',
            'message' => 'Hello, I would like to book this property.'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $response->assertSessionHasNoErrors();

    }
}
