<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('books')->insert([
            ['bookType' => 'Single Line', 'color' => 'Blue', 'coverType' => 'Card', 'pageNumber' => 60, 'gsm' => 55, 'price' => 1.80, 'image' => 'single-line.jpg'],
            ['bookType' => 'Small Square', 'color' => 'Blue', 'coverType' => 'Card', 'pageNumber' => 60, 'gsm' => 55, 'price' => 1.80, 'image' => 'small-square.jpg'],
            ['bookType' => 'Medium Small Square', 'color' => 'Blue', 'coverType' => 'Hard Cover', 'pageNumber' => 60, 'gsm' => 55, 'price' => 2.50, 'image' => 'medium-small-square.jpg'],
            ['bookType' => 'Medium Square', 'color' => 'Blue', 'coverType' => 'Card', 'pageNumber' => 60, 'gsm' => 55, 'price' => 1.80, 'image' => 'medium-square.jpg'],
            ['bookType' => 'R/B 4 Line', 'color' => 'Blue', 'coverType' => 'Hard Cover', 'pageNumber' => 60, 'gsm' => 55, 'price' => 2.50, 'image' => 'rb4-line.jpg'],
            ['bookType' => 'Music Book', 'color' => 'Blue', 'coverType' => 'Card', 'pageNumber' => 60, 'gsm' => 55, 'price' => 1.80, 'image' => 'music.jpg'],
            ['bookType' => 'Single Line', 'color' => 'Red', 'coverType' => 'Card', 'pageNumber' => 80, 'gsm' => 55, 'price' => 2.00, 'image' => 'single-line-red.jpg'],
            ['bookType' => 'Small Square', 'color' => 'Green', 'coverType' => 'Card', 'pageNumber' => 80, 'gsm' => 55, 'price' => 2.00, 'image' => 'small-square-green.jpg'],
            ['bookType' => 'Medium Small Square', 'color' => 'Yellow', 'coverType' => 'Hard Cover', 'pageNumber' => 80, 'gsm' => 55, 'price' => 2.70, 'image' => 'medium-small-square-yellow.jpg'],
            ['bookType' => 'Medium Square', 'color' => 'Purple', 'coverType' => 'Card', 'pageNumber' => 80, 'gsm' => 55, 'price' => 2.00, 'image' => 'medium-square-purple.jpg'],
            ['bookType' => 'R/B 4 Line', 'color' => 'Orange', 'coverType' => 'Card', 'pageNumber' => 80, 'gsm' => 55, 'price' => 2.00, 'image' => 'rb4-line-orange.jpg'],
            ['bookType' => 'Music Book', 'color' => 'Pink', 'coverType' => 'Hard Cover', 'pageNumber' => 80, 'gsm' => 55, 'price' => 2.60, 'image' => 'music-pink.jpg'],
            ['bookType' => 'Single Line', 'color' => 'Green', 'coverType' => 'Card', 'pageNumber' => 100, 'gsm' => 55, 'price' => 2.20, 'image' => 'single-line-green.jpg'],
            ['bookType' => 'Small Square', 'color' => 'Purple', 'coverType' => 'Card', 'pageNumber' => 100, 'gsm' => 55, 'price' => 2.20, 'image' => 'small-square-purple.jpg'],
            ['bookType' => 'Medium Small Square', 'color' => 'Orange', 'coverType' => 'Hard Cover', 'pageNumber' => 100, 'gsm' => 55, 'price' => 2.80, 'image' => 'medium-small-square-orange.jpg'],
            ['bookType' => 'Medium Square', 'color' => 'Red', 'coverType' => 'Card', 'pageNumber' => 100, 'gsm' => 55, 'price' => 2.20, 'image' => 'medium-square-red.jpg'],
            ['bookType' => 'R/B 4 Line', 'color' => 'Black', 'coverType' => 'Card', 'pageNumber' => 100, 'gsm' => 55, 'price' => 2.20, 'image' => 'rb4-line-black.jpg'],
            ['bookType' => 'Music Book', 'color' => 'Grey', 'coverType' => 'Card', 'pageNumber' => 100, 'gsm' => 55, 'price' => 2.20, 'image' => 'music-grey.jpg'],
            ['bookType' => 'Half Line', 'color' => 'Blue', 'coverType' => 'Card', 'pageNumber' => 60, 'gsm' => 55, 'price' => 1.80, 'image' => 'half-line.jpg'],
            ['bookType' => 'Large Square', 'color' => 'Red', 'coverType' => 'Hard Cover', 'pageNumber' => 60, 'gsm' => 55, 'price' => 2.50, 'image' => 'large-square.jpg'],
            ['bookType' => 'General Exercise Book', 'color' => 'Sky Blue', 'coverType' => 'Card', 'pageNumber' => 60, 'gsm' => 55, 'price' => 1.80, 'image' => 'general-exercise.jpg'],
            ['bookType' => 'Writing Book', 'color' => 'Brown', 'coverType' => 'Card', 'pageNumber' => 60, 'gsm' => 55, 'price' => 1.80, 'image' => 'writing.jpg'],
            ['bookType' => 'General Exercise Book', 'color' => 'Silver', 'coverType' => 'Card', 'pageNumber' => 80, 'gsm' => 55, 'price' => 2.00, 'image' => 'general-exercise-silver.jpg'],
            ['bookType' => 'Writing Book', 'color' => 'Maroon', 'coverType' => 'Hard Cover', 'pageNumber' => 80, 'gsm' => 55, 'price' => 2.60, 'image' => 'writing-maroon.jpg'],
        ]);
    }
}
