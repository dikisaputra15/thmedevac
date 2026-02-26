<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\Airport;
use App\Models\Embassiees;
use App\Models\Provincesregion;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
         $totalhospital = Hospital::count();
         $totalairport = Airport::count();
         $totalembassy = Embassiees::count();

        $airportNames = Airport::distinct()->pluck('airport_name')->filter()->sort()->values();
        $airportCategories = Airport::distinct()->pluck('category')->filter()->sort()->values();
        $airportLocations = Airport::distinct()->pluck('address')->filter()->sort()->values();

        $hospitalNames = DB::table('hospitals')->distinct()->pluck('name')->filter()->sort()->values();
        $hospitalCategories = DB::table('hospitals')->distinct()->pluck('facility_level')->filter()->sort()->values();
        $hospitalLocations = DB::table('hospitals')->distinct()->pluck('address')->filter()->sort()->values();
         $totalhospital = Hospital::count();

        $provinces = Provincesregion::all();

        return view('pages.dashboard',
            [
                'totalhospital' => $totalhospital,
                'totalairport' => $totalairport,
                'totalembassy' => $totalembassy,
                'airportNames' => $airportNames,
                'airportCategories' => $airportCategories,
                'airportLocations' => $airportLocations,
                'provinces' => $provinces,
                'hospitalNames' => $hospitalNames,
                'hospitalCategories' => $hospitalCategories,
                'hospitalLocations' => $hospitalLocations,
                'totalhospital' => $totalhospital
            ]
        );
    }

     public function administrator(Request $request)
    {
         $totalhospital = Hospital::count();
         $totalairport = Airport::count();
         $totalembassy = Embassiees::count();

        $airportNames = Airport::distinct()->pluck('airport_name')->filter()->sort()->values();
        $airportCategories = Airport::distinct()->pluck('category')->filter()->sort()->values();
        $airportLocations = Airport::distinct()->pluck('address')->filter()->sort()->values();

        $hospitalNames = DB::table('hospitals')->distinct()->pluck('name')->filter()->sort()->values();
        $hospitalCategories = DB::table('hospitals')->distinct()->pluck('facility_level')->filter()->sort()->values();
        $hospitalLocations = DB::table('hospitals')->distinct()->pluck('address')->filter()->sort()->values();
         $totalhospital = Hospital::count();

        $provinces = Provincesregion::all();

        return view('pages.admindashboard',
            [
                'totalhospital' => $totalhospital,
                'totalairport' => $totalairport,
                'totalembassy' => $totalembassy,
                'airportNames' => $airportNames,
                'airportCategories' => $airportCategories,
                'airportLocations' => $airportLocations,
                'provinces' => $provinces,
                'hospitalNames' => $hospitalNames,
                'hospitalCategories' => $hospitalCategories,
                'hospitalLocations' => $hospitalLocations,
                'totalhospital' => $totalhospital
            ]
        );
    }

    public function exurl(Request $request)
    {
        return view('pages.master.exurl');
    }
}
