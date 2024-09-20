<?php

use App\Models\Seasons;
use Illuminate\Support\Carbon;

if (!function_exists('current_season')) {
    function current_season()
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        return Seasons::where('start_date', '<=', $currentDate)
            ->where('end_date', '>=', $currentDate)
            ->first();
    }
}
