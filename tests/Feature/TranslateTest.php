<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TranslateTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $data = ['text' => 'sandalye' , 'source' => 'tr' , 'target' => 'fr'];
        $response = $this->json('POST', 'api/post',$data);

		$this->assertEquals(200,$response->getStatusCode());
    }

 
}
