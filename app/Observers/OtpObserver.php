<?php

namespace App\Observers;

use App\Models\Otp;

class OtpObserver
{
    /**
     * Handle the otp "creating" event.
     *
     * @param  \App\Models\Otp  $otp
     * @return void
     */
    public function creating(Otp $otp)
    {
        $otp->updated_at = null;
    }

    /**
     * Handle the otp "updated" event.
     *
     * @param  \App\Models\Otp  $otp
     * @return void
     */
    public function updated(Otp $otp)
    {
        //
    }

    /**
     * Handle the otp "deleted" event.
     *
     * @param  \App\Models\Otp  $otp
     * @return void
     */
    public function deleted(Otp $otp)
    {
        //
    }

    /**
     * Handle the otp "restored" event.
     *
     * @param  \App\Models\Otp  $otp
     * @return void
     */
    public function restored(Otp $otp)
    {
        //
    }

    /**
     * Handle the otp "force deleted" event.
     *
     * @param  \App\Models\Otp  $otp
     * @return void
     */
    public function forceDeleted(Otp $otp)
    {
        //
    }
}
