<?php
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

if (!function_exists('getUserSetting')) {
    
    function getUserSetting(string $key)
    {
        
        $user_setting =  Setting::where('user_id', Auth::id())
            ->where('key', $key)
            ->value('value');
        return $user_setting ? $user_setting : null;
    }
}
