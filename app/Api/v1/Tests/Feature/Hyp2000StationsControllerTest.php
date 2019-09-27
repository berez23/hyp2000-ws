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
    
    protected $uri                          = '/api/v1/hyp2000stations?sta=ACER&cha=HHZ&net=IV&cache=false';
    protected $hyp2000Input;
    protected $hyp2000OutputJsonExpected;
    
    public function setUp(): void {
        parent::setUp();

        // Get input
        $Hyp2000Input_class = new Hyp2000Input();
        $this->hyp2000Input = json_decode($Hyp2000Input_class->data, true);

        // Get output expected
        $Hyp2000OutputJson_class = new Hyp2000OutputJson();
        $this->hyp2000OutputJsonExpected = json_decode($Hyp2000OutputJson_class->data, true);
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