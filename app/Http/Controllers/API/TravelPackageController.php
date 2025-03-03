<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TravelPackageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
        ]);
    
        // Gunakan Google Maps API untuk mendapatkan koordinat dari alamat
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'address' => $request->location,
            'key' => $apiKey,
        ]);
    
        $data = $response->json();
        if (!empty($data['results'])) {
            $latitude = $data['results'][0]['geometry']['location']['lat'];
            $longitude = $data['results'][0]['geometry']['location']['lng'];
        } else {
            return response()->json(['error' => 'Lokasi tidak ditemukan'], 400);
        }
    
        // Simpan data wisata ke database
        $travelPackage = TravelPackage::create([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    
        return response()->json($travelPackage, 201);
    }
}