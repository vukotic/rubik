<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTest extends TestCase
{

    public function test_is_cube_recreated(){
        $response = $this->get('/api/cube/rotate/z/3/ccw');
        $response->assertstatus(200);
        $top_right_front_corner = ['x' => 1, 'y' => 1, 'z' => 1, 'cx' => 'W', 'cy' => 'B', 'cz' => 'R']; //coordinates and colors of the top right front corner
        $response = $this->get('/api/cube/recreate');
        $response->assertStatus(200);
        $response->assertJsonFragment($top_right_front_corner);
    }

    //----------------

    public function test_is_cube_rotated(){
        $top_right_front_corner = ['x' => 1, 'y' => 1, 'z' => 1, 'cx' => 'G', 'cy' => 'B', 'cz' => 'R']; //when the top ring is rotated CCW
        $this->get('api/cube/recreate');
        $response = $this->get('/api/cube/rotate/z/3/ccw');
        $response->assertStatus(200);
        $response->assertJsonFragment($top_right_front_corner);
    }

    //----------------

    public function test_is_cube_display_working(){
        $fresh_cube = [
            'F' =>
                [
                    0 => 'WWW',
                    1 => 'WWW',
                    2 => 'WWW',
                ],
            'B' =>
                [
                    0 => 'YYY',
                    1 => 'YYY',
                    2 => 'YYY',
                ],
            'U' =>
                [
                    0 => 'RRR',
                    1 => 'RRR',
                    2 => 'RRR',
                ],
            'D' =>
                [
                    0 => 'OOO',
                    1 => 'OOO',
                    2 => 'OOO',
                ],
            'L' =>
                [
                    0 => 'GGG',
                    1 => 'GGG',
                    2 => 'GGG',
                ],
            'R' =>
                [
                    0 => 'BBB',
                    1 => 'BBB',
                    2 => 'BBB',
                ],
        ];


        $cube_rotated_by_z_top_row_ccw = [
            'F' =>
                [
                    0 => 'GGG',
                    1 => 'WWW',
                    2 => 'WWW',
                ],
            'B' =>
                [
                    0 => 'BBB',
                    1 => 'YYY',
                    2 => 'YYY',
                ],
            'U' =>
                [
                    0 => 'RRR',
                    1 => 'RRR',
                    2 => 'RRR',
                ],
            'D' =>
                [
                    0 => 'OOO',
                    1 => 'OOO',
                    2 => 'OOO',
                ],
            'L' =>
                [
                    0 => 'YYY',
                    1 => 'GGG',
                    2 => 'GGG',
                ],
            'R' =>
                [
                    0 => 'WWW',
                    1 => 'BBB',
                    2 => 'BBB',
                ],
        ];
        $this->get('/api/cube/recreate');
        $response = $this->get('/api/cube/display');
        $response->assertStatus(200);
        $response->assertJson($fresh_cube);

        $this->get('/api/cube/rotate/z/3/ccw');
        $response = $this->get('/api/cube/display');
        $response->assertJson($cube_rotated_by_z_top_row_ccw);
    }
}
