<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verifyEmail($id)
    {
        $admin = Admin::findOrFail($id);
        if (!$admin->email_verified_at) {
            $admin->email_verified_at = now();
            $admin->save();
        }
        return response()->json(['message' => 'Email verified']);
    }    
}
