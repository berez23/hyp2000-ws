<?php

namespace App\Api\v1\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Api\Traits\UtilsTrait;
use App\Api\v1\Tests\Feature\Hyp2000Input;
use App\Api\v1\Tests\Feature\Hyp2000OutputJson;

class Hyp2000StationsControllerTest extends TestCase
{
    use UtilsTrait;
    
    protected $baseUri          = '/api/v1/hyp2000stations';
    protected $params           = 'sta=ACER&cha=HHZ&net=IV&cache=false';
    protected $uri;
    
    public function setUp(): void {
        parent::setUp();

        /* Set '$uri' */
        $this->uri = $this->baseUri.'?'. $this->params;
    }
    
    public function test_output() 
    {
        
        echo " uri:".$this->uri."\n";
        $response = $this->get($this->uri);
        
        $this->assertContains($response->status(), [200], $response->content());

        // Get output
        $data = $response->getContent();
        
        /* Check contentets */
        $substring = "ACER";
        $this->assertStringContainsString($substring, $response->getContent());
    }
}