<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Otp;
use App\Models\TradeHistory;
use App\Traits\OtpTrait;
use App\Http\Requests\AuthenticateRequest;
use App\Http\Requests\OtpVerificationRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\AuthResource;

class UserController extends Controller
{
    use OtpTrait;

    /* 
     * User Authentication 
     */
    public function store(AuthenticateRequest $request)
    {
        $user = User::firstOrCreate(['mobile' => $request->mobile]);
        $user->token = $user->createToken('auth')->plainTextToken;
        
        $generatedOtpObj = $this->generateOtp();

        $otpObj = new Otp();
        $otpObj->user_id = $user->id;
        $otpObj->otp = $generatedOtpObj->otp;
        $otpObj->otp_hash = $generatedOtpObj->hashedOtp;
        $otpObj->otp_expiry = Carbon::now()->addMinutes(10)->toDateTimeString();
        $otpObj->save();

        return response()->success(new AuthResource($user));
    }

    /* 
     * OTP Verification 
     */
    public function otpVerification(OtpVerificationRequest $request)
    {
        $user = auth()->user();
        
        $this->validateOtp($user, $request);

        $user->is_mobile_verified = 1;
        $user->otp->verified_at = Carbon::now()->toDateTimeString();

        DB::transaction(function () use ($user) {
            $user->otp->save();
            $user->save();
        }, 2);
        
        return response()->success([ 'token' => $user->createToken('device-id')->plainTextToken ], 'OTP Verified');
    }

    /*
     * Returns the details of the authenticated user
     */
    public function index()
    {
        return response()->success(new UserResource(auth()->user()));
    }

}
