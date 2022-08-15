<?php

namespace App\Lib\Interfaces;

interface RubikCubeInterface{
    public function rotate(string $axis, int $ring, string $direction);
    public function saveState();
    public function recreate();
    public function display();
    public function displaySide(string $side);
}
