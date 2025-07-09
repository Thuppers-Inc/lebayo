<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    /**
     * Obtenir l'adresse basée sur les coordonnées
     */
    public function reverseGeocode(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lon' => 'required|numeric|between:-180,180'
        ]);

        $lat = $request->lat;
        $lon = $request->lon;

        try {
            // Utiliser l'API Nominatim (OpenStreetMap) - gratuite
            $response = Http::withHeaders([
                'User-Agent' => 'Lebayo-App/1.0'
            ])->timeout(10)->get('https://nominatim.openstreetmap.org/reverse', [
                'format' => 'json',
                'lat' => $lat,
                'lon' => $lon,
                'zoom' => 10,
                'addressdetails' => 1
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['address'])) {
                    $address = $data['address'];
                    
                    // Extraire la commune/ville avec priorité
                    $city = $address['city'] ?? 
                           $address['town'] ?? 
                           $address['village'] ?? 
                           $address['municipality'] ?? 
                           $address['suburb'] ??
                           $address['county'] ??
                           'Ville inconnue';

                    return response()->json([
                        'success' => true,
                        'city' => $city,
                        'address' => $address,
                        'coordinates' => [
                            'lat' => $lat,
                            'lon' => $lon
                        ]
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Adresse introuvable'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Erreur géolocalisation', [
                'lat' => $lat,
                'lon' => $lon,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur de localisation'
            ], 500);
        }
    }

    /**
     * Obtenir la localisation basée sur l'IP (fallback)
     */
    public function getLocationByIp(Request $request)
    {
        try {
            $ip = $request->ip();
            
            // Pour les IP locales, utiliser une IP publique de test
            if ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.')) {
                $ip = '8.8.8.8'; // IP de Google pour test local
            }

            // Utiliser ipapi.co (gratuit)
            $response = Http::timeout(10)->get("https://ipapi.co/{$ip}/json/");

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['city']) && $data['city'] !== null) {
                    return response()->json([
                        'success' => true,
                        'city' => $data['city'],
                        'region' => $data['region'] ?? '',
                        'country' => $data['country_name'] ?? '',
                        'coordinates' => [
                            'lat' => $data['latitude'] ?? null,
                            'lon' => $data['longitude'] ?? null
                        ]
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Localisation par IP indisponible'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Erreur géolocalisation IP', [
                'ip' => $request->ip(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur de localisation'
            ], 500);
        }
    }
} 