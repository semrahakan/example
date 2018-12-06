<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

//file:///Users/semrahakan/Desktop/semra-hakan/site/storage/logs/phpunit/index.html
class GetWaitingTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $data = ['list' => ['elma','dolap'], 'source' => 'tr' , 'target' => 'fr'];
        $response = $this->json('POST', 'api/waiting',$data);

        $this->assertEquals(200,$response->getStatusCode());
    }
}
