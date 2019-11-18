<?php


namespace App\Interfaces;


interface JokeInterface
{
    public function getJoke(array $categories):array;
}