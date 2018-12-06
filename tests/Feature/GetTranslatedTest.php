<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetTranslatedTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
   public function testGetTranslatedSuccessfully(){

        
        $response = $this->get('/api/index');

        $response->assertStatus(200);

    }
}
