<?php

use App\Models\Category;
use App\User;
use Bezhanov\Faker\Provider\Commerce;
use bheller\ImagesGenerator\ImagesGeneratorProvider;
use Faker\Provider\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($index=0;$index<10;$index++){


            $faker = Faker\Factory::create();
            $faker->addProvider(new Commerce($faker));
            $faker->addProvider(new ImagesGeneratorProvider($faker));


            $fields = [
                'name'=>$faker->department,

            ];

            $image = $faker->imageGenerator(null,640,480,'png',true,null,$faker->hexColor);

            $contents = file_get_contents($image);
            $filename = 'categories/'.uniqid().'.png';
            Storage::disk('public')->put($filename, $contents);



            $fields['image']=$filename;

            if(mt_rand(0,1) && Category::all()->count()>0){
                $parent = Category::all()->random(1)->first();
                if($parent){
                    $fields['parent_id']=$parent->id;
                }
            }

            Category::create($fields);

        }


    }
}
