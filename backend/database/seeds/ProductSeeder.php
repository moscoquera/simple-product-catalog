<?php

use App\Models\Category;
use App\Models\Product;
use App\User;
use Bezhanov\Faker\Provider\Commerce;
use bheller\ImagesGenerator\ImagesGeneratorProvider;
use Faker\Provider\Company;
use Faker\Provider\Lorem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($index=0;$index<100;$index++){


            $faker = Faker\Factory::create();
            $faker->addProvider(new Commerce($faker));
            $faker->addProvider(new Lorem($faker));
            $faker->addProvider(new ImagesGeneratorProvider($faker));

            $parent = Category::query()->leaf()->inRandomOrder()->first();
            $fields = [
                'name'=>$faker->department,
                'description'=>$faker->text(2500),
                'usd_price'=>$faker->randomNumber(4),
                'weight'=>$faker->randomNumber(3),
                'category_id'=>$parent->id
            ];




            $product = Product::create($fields);



            $images=mt_rand(0,3);
            for($img_index=0;$img_index<$images;$img_index++){
                $image = $faker->imageGenerator(null,640,480,'png',true,null,$faker->hexColor);

                $contents = file_get_contents($image);
                $filename = 'products/'.uniqid().'.png';
                Storage::disk('public')->put($filename, $contents);
                $product->images()->create(['path'=>$filename]);
            }




        }


    }
}
