<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\Airport;
use App\Models\Provincesregion;
use Illuminate\Support\Facades\DB;

class HospitalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $hospitalNames = DB::table('hospitals')->distinct()->pluck('name')->filter()->sort()->values();
        $hospitalCategories = DB::table('hospitals')->distinct()->pluck('facility_level')->filter()->sort()->values();
        $hospitalLocations = DB::table('hospitals')->distinct()->pluck('address')->filter()->sort()->values();

        $provinces = Provincesregion::all();
        return view('pages.hospital.index', compact('provinces','hospitalNames','hospitalCategories','hospitalLocations'));
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
    //     return response()->json(Hospital::all());
    // }

    public function showdetail($id)
    {
        $hospital = Hospital::findOrFail($id);
        $city = DB::table('cities')->where('id', $hospital->city_id)->first();
        $province = DB::table('provincesregions')->where('id', $hospital->province_id)->first();

        $latitude = $hospital->latitude;
        $longitude = $hospital->longitude;
        $radius_km = 500; // Your desired radius

        // Fetch nearby hospitals (excluding the current one)
        $nearbyHospitals = Hospital::selectRaw("
            id, name, icon, latitude, longitude,
            ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance
        ", [$latitude, $longitude, $latitude])
        ->having('distance', '<=', $radius_km)
        ->where('id', '!=', $hospital->id) // Exclude the current hospital
        ->orderBy('distance')
        ->get();

        // Fetch nearby airports
        $nearbyAirports = Airport::selectRaw("
            id, airport_name AS name, icon, latitude, longitude,
            ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance
        ", [$latitude, $longitude, $latitude])
        ->having('distance', '<=', $radius_km)
        ->orderBy('distance')
        ->get();

        return view('pages.hospital.showdetail', compact('hospital','city','province','nearbyHospitals','nearbyAirports','radius_km'));
    }

    public function showdetailclinic($id)
    {
        $hospital = Hospital::findOrFail($id);
        return view('pages.hospital.showdetailclinic', compact('hospital'));
    }

    public function showdetailemergency($id)
    {
        $hospital = Hospital::findOrFail($id);

        $latitude = $hospital->latitude;
        $longitude = $hospital->longitude;
        $radius_km = 500; // Your desired radius

        // Fetch nearby hospitals (excluding the current one)
        $nearbyHospitals = Hospital::selectRaw("
            id, name, icon, latitude, longitude, facility_level, facility_category,
            ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance
        ", [$latitude, $longitude, $latitude])
        ->having('distance', '<=', $radius_km)
        ->where('id', '!=', $hospital->id) // Exclude the current hospital
        ->orderBy('distance')
        ->get();

         // Fetch nearby airports
        $nearbyAirports = Airport::selectRaw("
            id, airport_name AS name, icon, latitude, longitude, category,
            ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance
        ", [$latitude, $longitude, $latitude])
        ->having('distance', '<=', $radius_km)
        ->orderBy('distance')
        ->get();

        return view('pages.hospital.showdetailemergency', compact('hospital','nearbyHospitals','radius_km','nearbyAirports'));
    }

    public function filter(Request $request)
    {
        $query = Hospital::query();

        //  JOIN YANG BENAR & AMAN
        $query->leftJoin('cities', 'cities.id', '=', 'hospitals.city_id');
        $query->leftJoin('provincesregions', 'provincesregions.id', '=', 'hospitals.province_id');

        $query->select(
            'hospitals.*',
            'cities.city',
            'provincesregions.provinces_region'
        );

        $query->where('hospital_status', true);

        // Filter by name
        $query->when($request->filled('name'), function ($q) use ($request) {
            $q->where('hospitals.name', 'like', '%' . $request->name . '%');
        });

        // Filter by category
        $query->when($request->filled('category'), function ($q) use ($request) {
            $categories = $request->category;

            if (is_array($categories)) {
                $q->whereIn('hospitals.facility_level', $categories);
            } else {
                $q->where('hospitals.facility_level', 'like', '%' . $categories . '%');
            }
        });

        // Filter by location
        $query->when($request->filled('location'), function ($q) use ($request) {
            $q->where('hospitals.address', 'like', '%' . $request->location . '%');
        });

        // Filter by province
        $query->when($request->filled('provinces'), function ($q) use ($request) {
            $provinceIds = array_filter((array) $request->provinces, 'is_numeric');
            if (!empty($provinceIds)) {
                $q->whereIn('hospitals.province_id', $provinceIds);
            }
        });

        // Radius filter (Haversine)
        if (
            $request->filled('radius') &&
            $request->filled('center_lat') &&
            $request->filled('center_lng') &&
            is_numeric($request->radius) &&
            $request->radius > 0
        ) {
            $haversine = "(6371 * acos(
                cos(radians(?)) * cos(radians(latitude))
                * cos(radians(longitude) - radians(?))
                + sin(radians(?)) * sin(radians(latitude))
            ))";

            $query->selectRaw("$haversine AS distance", [
                    $request->center_lat,
                    $request->center_lng,
                    $request->center_lat
                ])
                ->whereRaw("$haversine < ?", [
                    $request->center_lat,
                    $request->center_lng,
                    $request->center_lat,
                    $request->radius
                ])
                ->orderBy('distance');
        }

        // Polygon filter tetap aman
        if ($request->filled('polygon')) {
            $polygon = json_decode($request->polygon, true);

            if (
                json_last_error() === JSON_ERROR_NONE &&
                isset($polygon['geometry']['coordinates'])
            ) {
                $coords = $polygon['geometry']['coordinates'][0];
                $wkt = implode(',', array_map(fn($p) => "{$p[0]} {$p[1]}", $coords));
                $query->whereRaw(
                    "ST_Within(POINT(longitude, latitude), ST_GeomFromText(?))",
                    ["POLYGON(($wkt))"]
                );
            }
        }

        return response()->json($query->get());
}

}
