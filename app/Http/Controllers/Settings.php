<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class Settings extends Controller
{
    public function businessDetails(Request $req)
    {
        $req->validate([
            'businessName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:11',
            'address' => 'required|string|max:255',
            'googleMapAddress' => 'required|string',
            'facebook' => 'required|string|max:255',
            'instagram' => 'required|string|max:255',
            'x' => 'required|string|max:255',
            'linkedIn' => 'nullable|string|max:255',
        ]);

        // Assuming you want to allow only one settings record
        $setting = Setting::first();

        if ($setting) {
            $setting->update($req->all());
            return response()->json([
                'success' => true,
                'message' => 'Your business details have been updated successfully.',
                'data' => $setting,
            ], 200);
        } else {
            $setting = Setting::create($req->all());
            return response()->json([
                'success' => true,
                'message' => 'Your business details have been saved successfully.',
                'data' => $setting,
            ], 201);
        }
    }



    public function fetchBusinessDetails()
    {
        $bussinessInfo = Setting::orderBy('id', 'desc')->first();
        return response()->json([
            'success' => true,
            'data' => $bussinessInfo,
        ]);
    }
}
