<?php

namespace App\Traits;

use Carbon\Carbon;

trait SerializeDate
{
    public function serializeTimezone($date = null)
    {
        return $date ? Carbon::createFromFormat('Y-m-d H:i:s', $date)->toDateTimeString() : null;
    }
}