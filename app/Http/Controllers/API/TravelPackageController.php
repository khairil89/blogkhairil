<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TravelPackageController extends Controller
{
    public function index()
    {
        $travelPackages = TravelPackage::paginate(10); // Pagination 10 item per halaman
        return response()->json($travelPackages);
    }
    
    public function show($id)
    {
        $travelPackage = TravelPackage::find($id);
        if (!$travelPackage) {
            return response()->json(['error' => 'Paket wisata tidak ditemukan'], 404);
        }
        return response()->json($travelPackage);
    }
    
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

    public function update(Request $request, $id)
    {
        $travelPackage = TravelPackage::find($id);
        if (!$travelPackage) {
            return response()->json(['error' => 'Paket wisata tidak ditemukan'], 404);
        }

        $request->validate([
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'location' => 'sometimes|string',
            'image' => 'sometimes|string|url',
            'contact_info' => 'sometimes|string',
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
        ]);

        $travelPackage->update($request->all());

        return response()->json($travelPackage, 200);
    }

    public function destroy($id)
    {
        $travelPackage = TravelPackage::find($id);
        if (!$travelPackage) {
            return response()->json(['error' => 'Paket wisata tidak ditemukan'], 404);
        }
    
        $travelPackage->delete();
        return response()->json(['message' => 'Paket wisata berhasil dihapus'], 200);
    }

}