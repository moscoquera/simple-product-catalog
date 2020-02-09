<?php

namespace Tests\Feature\Api;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CategoriesTest extends TestCase
{

    use RefreshDatabase;
    const BASE_URL='api/categories';
    const CATEGORY_FIELD = [
            'name' => '',
            'parent_id' => null,
        ];

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreate()
    {


        $response=$this->create();
        $response->assertStatus(401); //blocked due security, not auth user

        Passport::actingAs(factory(User::class)->create());

        //top category
        $response = $this->create('parent category');
        $response->assertOk()->assertJsonFragment((array('name'=>'parent category')+self::CATEGORY_FIELD))->assertJsonStructure([
                'name',
                'parent_id',
                'image',
                'id'
        ]);
        $parent = json_decode($response->getContent());
        $response = $this->create('child category',$parent->id);
        $response->assertOk()->assertJsonFragment((array('parent_id'=>$parent->id,'name'=>'child category')+self::CATEGORY_FIELD))->assertJsonStructure([
            'name',
            'parent_id',
            'image',
            'id'
        ]);
    }

    public function testIndex()
    {


        Passport::actingAs(factory(User::class)->create());
        $response = $this->create('parent category');
        $parent = json_decode($response->getContent());
        $response = $this->create('child category',$parent->id);
        $child = json_decode($response->getContent());

        Auth::user()->token()->revoke(); //as as public user
        $response=$this->get(self::BASE_URL);
        $response->assertOk()->assertJsonStructure(array(
            'data'=>array(
                array_keys(self::CATEGORY_FIELD),
                array_keys(self::CATEGORY_FIELD)
            )
        ))->assertJsonCount(2,'data');

        $this->assertTrue(collect($response->decodeResponseJson('data'))->every(function ($value) use($parent,$child){
            $value=(object)$value;
            if($value->id==$parent->id){
                return $value->parent_id==null;
            }
            if($value->id==$child->id){
                return $value->parent_id == $parent->id
                    && $value->parent
                    && $value->parent['id']==$parent->id;
            }

            return false;
        }));
    }

    public function testShow()
    {


        Passport::actingAs(factory(User::class)->create());
        $response = $this->create('parent category');
        $parent = json_decode($response->getContent());
        $response = $this->create('child category', $parent->id);
        $child = json_decode($response->getContent());

        Auth::user()->token()->revoke(); //as as public user
        $response = $this->get(self::BASE_URL . '/' .$parent->id);
        $response->assertOk()->assertJsonFragment((array('name' => $parent->name, 'id' => $parent->id) + self::CATEGORY_FIELD))->assertJsonStructure([
            'data'=>array(
                'name',
                'parent_id',
                'image',
                'id'
            )
        ]);
    }


    public function testUpdate()
    {


        Passport::actingAs(factory(User::class)->create());
        $response = $this->create('parent category');
        $original = json_decode($response->getContent());

        Auth::user()->token()->revoke(); //as as public user

        $new_values=array(
          'name'=>'updated_name',
          'image'=>$this->getRandomImage64()
        );

        $response = $this->put(self::BASE_URL . '/' .$original->id,$new_values);
        $response->assertOk()->assertJsonFragment(array('name'=>$new_values['name']))->assertJsonStructure([
                'name',
                'parent_id',
                'image',
                'id'

        ]);

        $this->assertTrue($original->image != json_decode($response->getContent())->image);
    }


    public function testDelete()
    {


        Passport::actingAs(factory(User::class)->create());
        $response = $this->create('parent category');
        $parent = json_decode($response->getContent());
        $response = $this->create('child category', $parent->id);
        $child = json_decode($response->getContent());

        $response = $this->delete(self::BASE_URL . '/' .$parent->id);
        $response->assertStatus(422)->assertExactJson([
            'message'=>'Category Must be empty'
        ]);


        $response = $this->delete(self::BASE_URL . '/' .$child->id);
        $response->assertOk();

        $response = $this->delete(self::BASE_URL . '/' .$parent->id);
        $response->assertOk();

    }

    private function getRandomImage64()
    {
        $directory = 'tests/Images/';
        $images = scandir($directory);
        $path = $directory . $images[mt_rand(2, sizeof($images) - 1)];
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    private function create($name='category_name',$parent_id=null){

        $category_fields=(array(
            'name'=>$name,
            'parent_id'=>$parent_id,
            'image' => $this->getRandomImage64()
        )+self::CATEGORY_FIELD);

        $response = $this->postJson(self::BASE_URL, $category_fields);

        return $response;
    }

}
