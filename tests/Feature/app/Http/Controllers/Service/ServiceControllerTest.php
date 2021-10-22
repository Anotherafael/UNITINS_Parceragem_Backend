<?php

namespace Tests\Feature\app\Http\Controllers\Service;

use Tests\TestCase;

class ServiceControllerTest extends TestCase
{
    public function testGetSections()
    {
        $code = 200;

        $response = $this->get(route('get_sections'));
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => true, 
            'message' => ''
        ]);
        $response->assertJsonStructure([
            'data',
        ]);
    }

    public function testGetProfessions()
    {
        $code = 200;

        $response = $this->get(route('get_professions'));
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => true, 
            'message' => ''
        ]);
        $response->assertJsonStructure([
            'data',
        ]);
    }

    public function testGetTasks()
    {
        $code = 200;

        $response = $this->get(route('get_tasks'));
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => true, 
            'message' => ''
        ]);
        $response->assertJsonStructure([
            'data',
        ]);
    }
}
