<?php

namespace App\Lib\Classes;

use App\Lib\Interfaces\RubikCubeInterface;
use App\Models\EuclideanBlock;

class EuclideanCube implements RubikCubeInterface{

    private array $blocks;

    //-----------------------------

    public function __construct(){

        $saved_blocks = EuclideanBlock::select('x', 'y', 'z', 'cx', 'cy', 'cz')->get();
        if(!count($saved_blocks))
            $this->createCube();
        else
            foreach($saved_blocks as $block){
                $this->blocks[] = $block;
            }
    }

    //-----------------------------

    public function rotate($axis, $ring, $direction){
        $horizontal = '';
        $vertical = '';
        $rotation = $direction == strtolower('ccw') ? 1 : -1;
        $rings = [1 => -1, 2 => 0, 3 => 1];
        $ring = $rings[$ring];
        switch($axis){
            case 'x':
                $vertical = 'z';
                $horizontal = 'y';
                break;
            case 'y':
                $vertical = 'z';
                $horizontal = 'x';
                break;
            case 'z':
                $vertical = 'y';
                $horizontal = 'x';
                break;
            default:
                return 'Invalid axis';

        }
        $color_h = 'c' . $horizontal;
        $color_v = 'c' . $vertical;

        foreach($this->blocks as $block){
            if($block->{$axis} == $ring){
                $t = $block->{$horizontal};
                $block->{$horizontal} = - $rotation * $block->{$vertical};
                $block->{$vertical} = $rotation * $t;
                $t = $block->{$color_h};
                $block->{$color_h} = $block->{$color_v};
                $block->{$color_v} = $t;
            }
        }
        $this->saveState();
        return $this->blocks;
    }

    //-----------------------------

    public function saveState(){
        EuclideanBlock::query()->truncate();
        //this could be done in Laravel with array, but since there's no danger of SQL injection, it's quicker with a raw query
        $query = 'INSERT INTO euclidean_blocks(`x`, `y`, `z`, `cx`, `cy`, `cz`, `created_at`, `updated_at`) VALUES ';
        foreach($this->blocks as $block){
            $query .= sprintf("(%d, %d, %d,'%s','%s','%s', NOW(), NOW()),", $block->x, $block->y, $block->z, $block->cx, $block->cy, $block->cz);
        }
        $query = substr($query, 0, -1) . ';'; //replace last comma with a semicolon

        \DB::insert($query);
    }

    //-----------------------------

    private function createCube(){
        /* the cube initial colors are
         *          Red
         *  Green   White   Blue   Yellow
         *          Orange
         */
        for($x=-1; $x<2; $x++)
            for($y=-1; $y<2; $y++)
                for($z=-1; $z<2; $z++){
                    $c = new EuclideanBlock();
                    $c->x = $x;
                    $c->y = $y;
                    $c->z = $z;
                    switch($x){
                        case 1:
                            $c->cx = 'W';
                            break;
                        case -1:
                            $c->cx = 'Y';
                    }
                    switch($y){
                        case 1:
                            $c->cy = 'B';
                            break;
                        case -1:
                            $c->cy = 'G';
                    }
                    switch($z){
                        case 1:
                            $c->cz = 'R';
                            break;
                        case -1:
                            $c->cz = 'O';
                    }
                    $this->blocks[] = $c;

                }
    }

    //-----------------------------

    public function recreate(){
        $this->createCube();
        $this->saveState();
        return $this->blocks;
    }

    //-----------------------------

    public function display(){
        $sides = ['F', 'B', 'U', 'D', 'L', 'R'];
        $cube = [];
        foreach($sides as $side){
            $cube[$side] = $this->drawSide($side);
        }
        return $cube;
    }

    //-----------------------------

    private function drawSide($side){
        //This actually displays properly only when the cube is rotated by Z axis

        //Sides are F(ront), B(ack), U(p), D(own), L(eft), R(ight)
        $coordinate = 1; //case when $side is Front, Up or Right
        if(!in_array($side, ['F', 'U', 'R']))
            $coordinate = -1;

        $axis = 'x'; //case when $side is 'F' or 'B'
        $vertical = 'z';
        $horizontal = 'y';
        if($side == 'L' || $side == 'R'){
            $axis = 'y';
            $vertical = 'z';
            $horizontal = 'x';
        }
        if($side == 'U' || $side == 'D'){
            $axis = 'z';
            $vertical = 'y';
            $horizontal = 'x';
        }

        $top_row = [];
        $middle_row = [];
        $bottom_row = [];

        $color = 'c' . $axis;

        foreach($this->blocks as $block){
            if($block->{$axis} == $coordinate){
                switch($block->{$vertical}){
                    case 1:
                        $top_row[$block->{$horizontal} +1] = $block->{$color};
                        break;
                    case 0:
                        $middle_row[$block->{$horizontal}+1] = $block->{$color};
                        break;
                    case -1:
                        $bottom_row[$block->{$horizontal}+1] = $block->{$color};
                }

            }
        }
        $r = [];
        ksort($top_row);
        ksort($middle_row);
        ksort($bottom_row);
        $r[] = implode($top_row);
        $r[] = implode($middle_row);
        $r[] = implode($bottom_row);
        return $r;
    }

    //-------------------------

    public function getBlocks(){
        return $this->blocks;
    }

    //-------------------------

    public function displaySide(string $side){
        return $this->drawSide(strtoupper($side));
    }
}
