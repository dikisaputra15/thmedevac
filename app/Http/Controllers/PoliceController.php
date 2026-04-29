<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Police;
use App\Models\Provincesregion;
use App\Models\City;
use Illuminate\Support\Facades\DB;
use Exception;

class PoliceController extends Controller
{
     public function index(Request $request)
    {
        // Using `unique()` and `values()` on collections ensures distinct, sorted results.
        // `filter()` removes null/empty values.
        $policeNames = Police::distinct()->pluck('name_police')->filter()->sort()->values();
        $policeCategories = Police::distinct()->pluck('category')->filter()->sort()->values();
        $policeLocations = Police::distinct()->pluck('location')->filter()->sort()->values();

        $provinces = Provincesregion::all(); // Get all provinces

        return view('pages.police.index', compact('provinces', 'policeNames', 'policeCategories', 'policeLocations'));
    }

     public function filter(Request $request)
    {
        $query = Police::query();

        $query->where('police_status', true);

        // 1. Filter by Airport Name (case-insensitive search)
        $query->when($request->filled('name'), function ($q) use ($request) {
            $q->where('name_police', 'like', '%' . $request->input('name') . '%');
        });

        // 2. Filter by Category (case-insensitive search)
        $query->when($request->filled('category'), function ($q) use ($request) {
            $q->where('category', $request->input('category'));
        });

        // 3. Filter by Location (Address - case-insensitive search)
        $query->when($request->filled('location'), function ($q) use ($request) {
            $q->where('location', 'like', '%' . $request->input('location') . '%');
        });

        // 4. Filter by Province IDs
        $query->when($request->filled('provinces'), function ($q) use ($request) {
            // Ensure province IDs are an array and valid integers
            $provinceIds = array_filter((array) $request->input('provinces'), 'is_numeric');
            if (!empty($provinceIds)) {
                $q->whereIn('province_id', $provinceIds);
            }
        });

        // 5. Filter by Radius (Haversine Formula)
        if (
            $request->filled('radius') &&
            $request->filled('center_lat') &&
            $request->filled('center_lng') &&
            is_numeric($request->input('radius')) &&
            $request->input('radius') > 0
        ) {
            $centerLat = (float) $request->input('center_lat');
            $centerLng = (float) $request->input('center_lng');
            $radiusKm = (float) $request->input('radius');

            // Haversine formula calculation for distance in kilometers
            // Note: This requires 'latitude' and 'longitude' columns in your 'airports' table
            $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(latitude))
                            * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))";

            $query->selectRaw("police.*, $haversine AS distance", [
                    $centerLat, $centerLng, $centerLat
                ])
                ->whereRaw("$haversine < ?", [
                    $centerLat, $centerLng, $centerLat, $radiusKm // Pass radius for comparison
                ])
                ->orderBy('distance');
        }

        // 6. Filter by GeoJSON Polygon (Geofencing)
        // This assumes you are using a database with spatial extensions like PostGIS or MySQL Spatial.
        // For PostGIS, you'd typically use `ST_GeomFromGeoJSON` and `ST_Within` or `ST_Intersects`.
        // For MySQL, you'd use `ST_GeomFromText` (from WKT) and `ST_Within` or `ST_Intersects`.
        // The implementation here is a placeholder and assumes a PostGIS setup or similar.
       if ($request->filled('polygon')) {
            try {
                $polygonGeoJSON = json_decode($request->input('polygon'), true);

                if (json_last_error() === JSON_ERROR_NONE && isset($polygonGeoJSON['geometry']['coordinates'])) {
                    $geometryType = $polygonGeoJSON['geometry']['type'];

                    if ($geometryType === 'Polygon') {
                        // Ambil koordinat luar dari polygon
                        $coordinates = $polygonGeoJSON['geometry']['coordinates'][0]; // Koordinat luar (outer ring)

                        // Konversi ke format WKT: "lng lat"
                        $wktCoords = implode(',', array_map(function ($point) {
                            return $point[0] . ' ' . $point[1]; // lng lat
                        }, $coordinates));

                        // Buat string WKT POLYGON
                        $wktPolygon = "POLYGON(($wktCoords))";

                        // Gunakan ST_Within + ST_GeomFromText (MySQL Spatial)
                        $query->whereRaw("ST_Within(POINT(longitude, latitude), ST_GeomFromText(?))", [$wktPolygon]);

                    } elseif ($geometryType === 'Point' && isset($polygonGeoJSON['properties']['radius'])) {
                        // Tangani Circle (Leaflet.draw) menggunakan Haversine
                        $centerLat = $polygonGeoJSON['geometry']['coordinates'][1]; // y = lat
                        $centerLng = $polygonGeoJSON['geometry']['coordinates'][0]; // x = lng
                        $radiusMeters = $polygonGeoJSON['properties']['radius'];

                        $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(latitude))
                                        * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))";

                        $query->whereRaw("$haversine < ?", [
                            $centerLat, $centerLng, $centerLat, $radiusMeters / 1000 // m to km
                        ]);
                    } else {
                        // \Log::warning("Unsupported GeoJSON geometry type: " . $geometryType);
                    }
                } else {
                    // \Log::warning("Invalid or malformed GeoJSON: " . $request->input('polygon'));
                }
            } catch (Exception $e) {
                // \Log::error("Error processing polygon filter: " . $e->getMessage());
            }
        }


        // Execute the query and return JSON response
        $police = $query->get();
        return response()->json($police);
    }

    public function showdetail($id)
    {
        $police = Police::findOrFail($id);
        $city = DB::table('cities')->where('id', $police->city_id)->first();
        $province = DB::table('provincesregions')->where('id', $police->province_id)->first();
        return view('pages.police.showdetail', compact('police','city','province'));
    }
}
