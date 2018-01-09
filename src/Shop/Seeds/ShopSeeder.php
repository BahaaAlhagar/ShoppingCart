<?php

use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Shop\Product::create([
        	'name' 		  => 'Test product',
        	'price' 	  => 10,
        	'description' => 'this is a test product!',
        	'imagePath'   => 'item.png',
        	]);

        \App\Shop\Product::create([
        	'name' 		  => 'another Product',
        	'price' 	  => 7.5,
        	'description' => 'this is another test product!',
        	'imagePath'   => 'item.png',
        	]);
    }
}
