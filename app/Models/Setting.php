<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['user_id', 'key', 'value'];

    // protected $casts = [
    //     'value' => 'array',
    // ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A method to get a setting by key for a specific user
    public static function getSetting($userId, $key)
    {
        //$theme = Setting::getSetting($userId, 'theme');
        $setting = self::where('user_id', $userId)->where('key', $key)->first();
        return $setting ? $setting->value : null;
    }

    // A method to update or create a setting for a specific user
    public static function updateSetting($userId, $key, $value)
    {
        //Setting::updateSetting($userId, 'theme', 'dark');
        return self::updateOrCreate(
            ['user_id' => $userId, 'key' => $key],
            ['value' => $value]
        );
    }
}
