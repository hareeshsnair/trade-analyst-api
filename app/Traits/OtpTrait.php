<?php

namespace App\Traits;

use Illuminate\Support\Facades\Hash;
use App\Models\Otp;
use Carbon\Carbon;

trait OtpTrait
{
    protected $otp;
    protected $hashedOtp;

    public function generateOtp()
    {
        $this->otp = rand(1000,9999);
        $this->hashedOtp = $this->getHashedOtp($this->otp);

        return $this;
    }

    public function getOtp()
    {
        return $this->otp;
    }

    public function setOtp($otp)
    {
        $this->otp = $otp;
        return $this;
    }

    public function getHashedOtp($otp = '')
    {
        return $otp ? Hash::make($otp) : $this->hashedOtp;
    }

    public function verifyOtp(Otp $model, $otp)
    {
        return Hash::check($otp, $model->otp_hash);
    }

    public function isOtpExpired(Otp $model)
    {
        return Carbon::now()->greaterThan($model->otp_expiry);
    }

    public function validateOtp($user, $request)
    {
        if(!$user->otp) {
            abort(403, 'You have not requested for an OTP');
        }
        if($this->isOtpExpired($user->otp)) {
            abort(422, 'OTP expired');
        }
        if($user->otp->verified_at || !$this->verifyOtp($user->otp, $request->otp)) {
            abort(422, 'Invalid OTP');
        }
    }
}