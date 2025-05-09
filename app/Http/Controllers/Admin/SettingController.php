<?php

namespace App\Http\Controllers\Admin;
use App\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class SettingController extends Controller
{
    private $view_path = "backend.settings";
    function index(){

        $settings = Setting::where('user_id', Auth::id())
        ->pluck('value', 'key')
        ->toArray();
        return view($this->view_path.'/index', ['settings' => $settings]);

    }

    public function store(Request $request)
    {
        //return $request;
        foreach ($request->input('settings', []) as $key => $value) {
 
            Setting::updateOrCreate(
                [
                    'key' => $key,
                    'user_id' => auth()->id(),
                ],
                [
                    'value' => $value,
                ]
            );
        }

        return redirect()->route('admin.settings')->with('status', [
            'icon' => 'success',
            'message' => 'Settings saved successfully!'
        ]);
        

    }
}
