<?php

namespace App\Providers;

use App\Services\EmailTokenAuthProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Srmklive\Authy\Services\Authy', function () {
            return new EmailTokenAuthProvider();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('is_base64_img',function($attribute, $value, $params, $validator) {

            $imageInfo = explode(";base64,", $value);
            $imgExt = str_replace('data:image/', '', $imageInfo[0]);

            if(sizeof($imageInfo)<2){
                return false;
            }

            $image = str_replace(' ', '+', $imageInfo[1]);

            $image = base64_decode($image);
            $f = finfo_open();
            $result = finfo_buffer($f, $image, FILEINFO_MIME_TYPE);
            return substr($result,0,5)=='image';
        });
    }
}
