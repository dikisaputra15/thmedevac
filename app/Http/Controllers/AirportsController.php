<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airport;
use App\Models\Hospital;
use App\Models\Provincesregion;
use App\Models\City;
use Illuminate\Support\Facades\DB;
use Exception; // Import Exception for better error handling

class AirportsController extends Controller
{
    /**
     * Display a listing of the resource (for the main map page).
     * This method prepares data for the frontend view.
     */
    public function index(Request $request)
    {
        // Using `unique()` and `values()` on collections ensures distinct, sorted results.
        // `filter()` removes null/empty values.
        $airportNames = Airport::distinct()->pluck('airport_name')->filter()->sort()->values();
        $airportCategories = Airport::distinct()->pluck('category')->filter()->sort()->values();
        $airportLocations = Airport::distinct()->pluck('address')->filter()->sort()->values();

        $provinces = Provincesregion::all(); // Get all provinces

        return view('pages.airports.index', compact('provinces', 'airportNames', 'airportCategories', 'airportLocations'));
    }

    /**
     * API endpoint to filter airports based on various criteria, including geofencing.
     * This method handles the filtering logic for AJAX requests from the map.
     */
    public function filter(Request $request)
    {
        $query = Airport::query();

        $query->where('airport_status', true);

        // 1. Filter by Airport Name (case-insensitive search)
        $query->when($request->filled('name'), function ($q) use ($request) {
            $q->where('airport_name', 'like', '%' . $request->input('name') . '%');
        });

        // 2. Filter by Category (case-insensitive search)
       $query->when($request->filled('categories'), function ($q) use ($request) {
            $categories = (array) $request->input('categories');

            $q->where(function ($sub) use ($categories) {
                foreach ($categories as $cat) {
                    // Cari kategori yang mengandung kata tersebut (case-insensitive)
                    $sub->orWhere('category', 'like', "%$cat%");
                }
            });
        });

        // 3. Filter by Location (Address - case-insensitive search)
        $query->when($request->filled('location'), function ($q) use ($request) {
            $q->where('address', 'like', '%' . $request->input('location') . '%');
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

            $query->selectRaw("airports.*, $haversine AS distance", [
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
        $airports = $query->get();
        return response()->json($airports);
    }

    // Unchanged methods for other functionalities

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function showdetail($id)
    {
        $airport = Airport::findOrFail($id);
        $city = City::findOrFail($airport->city_id);
        $province = Provincesregion::findOrFail($airport->province_id);

        return view('pages.airports.showdetail', compact('airport', 'city', 'province'));
    }

    public function showdetailemergency($id)
    {
        $airport = Airport::findOrFail($id);
        $hospital = Hospital::select('medical_support_website','travel_agent')->first();

          // --- Ambil Bandara Terdekat ---
        $nearbyAirports = Airport::selectRaw('*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance', [$airport->latitude, $airport->longitude, $airport->latitude])
            ->having('distance', '<=', 500) // Filter dalam radius 100 km (sesuaikan)
            ->where('id', '!=', $airport->id) // Jangan sertakan bandara utama itu sendiri
            ->orderBy('distance')
            ->get();

        // --- Ambil Rumah Sakit Terdekat ---
        $nearbyHospitals = Hospital::selectRaw('*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance', [$airport->latitude, $airport->longitude, $airport->latitude])
            ->having('distance', '<=', 500) // Filter dalam radius 100 km (sesuaikan)
            ->orderBy('distance')
            ->get();

        $radius_km = 500; // Radius lingkaran untuk ditampilkan di peta

        return view('pages.airports.showdetailemergency', compact('airport', 'nearbyAirports', 'nearbyHospitals', 'radius_km', 'hospital'));
    }

    public function showairlinesdestination($id)
    {
        $airport = Airport::findOrFail($id);
        return view('pages.airports.showairlinesdestination', compact('airport'));
    }

    public function shownavigation($id)
    {
        $airport = Airport::findOrFail($id);

           // --- Ambil Bandara Terdekat ---
        $nearbyAirports = Airport::selectRaw('*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance', [$airport->latitude, $airport->longitude, $airport->latitude])
            ->having('distance', '<=', 500) // Filter dalam radius 500 km (sesuaikan)
            ->where('id', '!=', $airport->id) // Jangan sertakan bandara utama itu sendiri
            ->orderBy('distance')
            ->get();

        $radius_km = 500;

        return view('pages.airports.shownavigation', compact('airport','nearbyAirports','radius_km'));
    }
}
