<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Embassiees;
use App\Models\Provincesregion;
use Illuminate\Support\Facades\DB;

class EmbassieesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $embassyNames = DB::table('embassiees')->distinct()->pluck('name_embassiees')->filter()->sort()->values();
        $embassyLocations = DB::table('embassiees')->distinct()->pluck('location')->filter()->sort()->values();

        $provinces = Provincesregion::all();
        return view('pages.embassiees.index', compact('provinces','embassyNames','embassyLocations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // public function api()
    // {
    //     return response()->json(Embassiees::all());
    // }

    public function showdetail($id)
    {
        $embassy = Embassiees::findOrFail($id);
        $city = DB::table('cities')->where('id', $embassy->city_id)->first();
        $province = DB::table('provincesregions')->where('id', $embassy->province_id)->first();
        return view('pages.embassiees.showdetail', compact('embassy','city','province'));
    }

    public function filter(Request $request)
    {
        $query = Embassiees::query();

        $query->where('embassy_status', true);

        // Filter by name
        $query->when($request->filled('name'), function ($q) use ($request) {
            $q->where('name_embassiees', 'like', '%' . $request->input('name') . '%');
        });

        // Filter by location
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

        // Filter by radius (Haversine Formula)
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

            // Haversine formula
            $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(latitude))
                        * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))";

            $query->selectRaw("embassiees.*, $haversine AS distance", [
                    $centerLat, $centerLng, $centerLat
                ])
                ->whereRaw("$haversine < ?", [
                    $centerLat, $centerLng, $centerLat, $radiusKm
                ])
                ->orderBy('distance');
        }

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
        $embessy = $query->get();
        return response()->json($embessy);
    }
}
