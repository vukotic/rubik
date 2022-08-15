<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Lib\Classes\EuclideanCube;

class ECubeTest extends TestCase
{
    public function test_is_cube_created(){
        $cube = new EuclideanCube();
        $blocks = $cube->getBlocks();
        $this->assertIsArray($blocks);
        foreach($blocks as $block){
            $this->assertIsObject($block);
            $this->assertIsInt($block->x);
            $this->assertIsString($block->cx);
        }
    }

    //----------------

    public function test_is_side_displayed_properly(){
        $cube = new EuclideanCube();
        $cube->recreate();
        $f = ['WWW', 'WWW', 'WWW'];
        $this->assertEquals($f, $cube->displaySide('F'));
    }
}
