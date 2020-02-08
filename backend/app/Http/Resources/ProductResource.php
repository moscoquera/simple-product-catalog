<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data =  parent::toArray($request);
        $data['images']=[];
        foreach ($this->images as $img){
            $data['images'][]=Storage::url($img->path);
        }
        $data['category']=CategoryResource::make($this->whenLoaded('category'));


        return $data;
    }
}
