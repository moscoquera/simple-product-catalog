<?php


namespace App\Services;

use App\Mail\AuthenticationToken;
use App\Models\AuthToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Srmklive\Authy\Contracts\Auth\TwoFactor\Provider as BaseProvider;
use Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;

class EmailTokenAuthProvider implements BaseProvider
{


    /**
     * Determine if the given user has two-factor authentication enabled.
     *
     * @param \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     *
     * @return bool
     */
    public function isEnabled(TwoFactorAuthenticatable $user)
    {
        return true; //enabled for all users
    }

    /**
     * Register the given user with the provider.
     *
     * @param \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @param bool $sms
     *
     * @return void
     */
    public function register(TwoFactorAuthenticatable $user, $sms = false)
    {

    }

    /**
     * Determine if the given token is valid for the given user.
     *
     * @param \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     * @param string $token
     *
     * @return bool
     */
    public function tokenIsValid(TwoFactorAuthenticatable $user, $token)
    {
        $auth_token = AuthToken::query()->where('user_id',$user->id)->first();
        if($auth_token){

            $diff_in_minutes=Carbon::now()->diffInMinutes($auth_token->created_at);
            if(Hash::check($token,$auth_token->value) && $diff_in_minutes<5){
                $this->delete($user);
                return true;
            }
        }

        return false;
    }

    /**
     * Delete the given user from the provider.
     *
     * @param \Srmklive\Authy\Contracts\Auth\TwoFactor\Authenticatable $user
     *
     * @return bool
     */
    public function delete(TwoFactorAuthenticatable $user)
    {
        AuthToken::query()->where('user_id',$user->id)->delete();
    }

    public function send(TwoFactorAuthenticatable $user){
        $address=$user->getEmailForTwoFactorAuth();
        $token=$this->setToken($user);
        Mail::to($address)->send(new AuthenticationToken($token));

    }

    /**
     * sets the user token used in the authentication
     * @param TwoFactorAuthenticatable $user
     * @return string
     */
    private function setToken(TwoFactorAuthenticatable $user){
        $token=$this->generateToken();
        $this->delete($user);

        AuthToken::create([
            'user_id'=>$user->id,
            'value'=>Hash::make($token)
        ]);

        return $token;

    }


    private function generateToken(){
        return "".mt_rand(100000,999999);
    }

}
