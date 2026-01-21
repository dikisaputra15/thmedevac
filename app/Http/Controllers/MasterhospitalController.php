<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\Provincesregion;
use App\Models\City;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MasterhospitalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         if(request()->ajax()) {
            return datatables()->of(Hospital::select(
                'hospitals.*',
                'cities.city as citi' // kolom dari tabel city
            )
            ->join('cities', 'cities.id', '=', 'hospitals.city_id') // join ke tabel city
            ->orderBy('hospitals.id', 'desc'))

            ->addColumn('created_at', function ($row) {
                // Format tanggal jadi dd-mm-yyyy HH:MM
                return Carbon::parse($row->created_at)->format('Y-m-d H:i:s');
            })
            ->addColumn('action', function($row){
                 $updateButton = '<a href="' . route('hospitaldata.edit', $row->id) . '" class="btn btn-primary btn-sm">Edit</a>';
                 $deleteButton = '<button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'">Delete</button>';

                  if ($row->hospital_status) {
                        // Kalau status = true (publish), tombol jadi Unpublish
                        $statusButton = '<button class="btn btn-sm btn-warning status-btn" data-id="'.$row->id.'">Unpublish</button>';
                    } else {
                        // Kalau status = false (unpublish), tombol jadi Publish
                        $statusButton = '<button class="btn btn-sm btn-success status-btn" data-id="'.$row->id.'">Publish</button>';
                    }

                 return $updateButton." ".$deleteButton." ".$statusButton;
            })
            ->rawColumns(['action','created_at'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('pages.master.hospital');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Provincesregion::orderByRaw('LOWER(provinces_region) ASC')->get();
        return view('pages.master.createhospital', [
            'provinces' => $provinces
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $hospital = new Hospital();

         if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $randomName = Str::random(40) . '.' . $extension;

            // Simpan di folder 'images' di disk 'public'
            $path = $request->file('image')->storeAs('image/hospital', $randomName, 'public');

            // Simpan path ke database
            $hospital->image = 'storage/'.$path;

         }


        $hospital->province_id = $request->input('province_id');
        $hospital->city_id = $request->input('city');
        $hospital->name = $request->input('name');
        $hospital->latitude = $request->input('latitude');
        $hospital->longitude = $request->input('longitude');
        $hospital->address = $request->input('address');
        $hospital->facility_level = $request->input('facility_level');
        $hospital->facility_category = $request->input('facility_category');
        $hospital->status = $request->input('status');
        $hospital->hrs_of_operation = $request->input('hrs_of_operation');
        $hospital->number_of_beds = $request->input('number_of_beds');
        $hospital->population_catchment = $request->input('population_catchment');
        $hospital->ownership = $request->input('ownership');
        $hospital->telephone = $request->input('telephone');
        $hospital->fax = $request->input('fax');
        $hospital->email = $request->input('email');
        $hospital->website = $request->input('website');
        $hospital->evacuation_option = $request->input('evacuation_option');
        $hospital->medical_support_website = $request->input('medical_support_website');
        $hospital->inpatient_services = $request->input('inpatient_services');
        $hospital->outpatient_services = $request->input('outpatient_services');
        $hospital->hour_emergency_services = $request->input('hour_emergency_services');
        $hospital->ambulance = $request->input('ambulance');
        $hospital->helipad = $request->input('helipad');
        $hospital->comments = $request->input('comments');
        $hospital->icu = $request->input('icu');
        $hospital->medical = $request->input('medical');
        $hospital->pediatric = $request->input('pediatric');
        $hospital->maternal = $request->input('maternal');
        $hospital->dental = $request->input('dental');
        $hospital->optical = $request->input('optical');
        $hospital->ioc = $request->input('ioc');
        $hospital->laboratory = $request->input('laboratory');
        $hospital->pharmacy = $request->input('pharmacy');
        $hospital->medical_imaging = $request->input('medical_imaging');
        $hospital->medical_student_training = $request->input('medical_student_training');
        $hospital->medical_personel_disclaimer = $request->input('medical_personel_disclaimer');
        $hospital->doctors = $request->input('doctors');
        $hospital->nurses = $request->input('nurses');
        $hospital->dental_therapist = $request->input('dental_therapist');
        $hospital->laboratory_assistants = $request->input('laboratory_assistants');
        $hospital->community_health = $request->input('community_health');
        $hospital->health_inspectors = $request->input('health_inspectors');
        $hospital->malaria_control_officers = $request->input('malaria_control_officers');
        $hospital->health_extention_officers = $request->input('health_extention_officers');
        $hospital->casuals = $request->input('casuals');
        $hospital->others = $request->input('others');
        $hospital->nearest_airfield = $request->input('nearest_airfield');
        $hospital->nearest_police_station = $request->input('nearest_police_station');
        $hospital->nearest_accommodation = $request->input('nearest_accommodation');
        $hospital->travel_agent = $request->input('travel_agent');
        $hospital->other_medical_info = $request->input('other_medical_info');
        $hospital->icon = $request->input('icon');
        $hospital->save();
        return redirect()->route('hospitaldata.index')->with('success', 'Data Succesfully Save');
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
    public function edit($id)
    {
        $hospital = Hospital::findOrFail($id);
        $provinces = Provincesregion::orderByRaw('LOWER(provinces_region) ASC')->get();
        $cities = City::orderByRaw('LOWER(city) ASC')->get();
        return view('pages.master.edithospital', [
            'hospital' => $hospital,
            'provinces' => $provinces,
            'cities' => $cities
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
         // Cari data airport berdasarkan ID
        $hospital = Hospital::findOrFail($id);

        // Update data
        $data = [
            'province_id' => $request->input('province_id'),
            'city_id' => $request->input('city'),
            'name' => $request->input('name'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'address' => $request->input('address'),
            'facility_level' => $request->input('facility_level'),
            'facility_category' => $request->input('facility_category'),
            'status' => $request->input('status'),
            'hrs_of_operation' => $request->input('hrs_of_operation'),
            'number_of_beds' => $request->input('number_of_beds'),
            'population_catchment' => $request->input('population_catchment'),
            'ownership' => $request->input('ownership'),
            'telephone' => $request->input('telephone'),
            'fax' => $request->input('fax'),
            'email' => $request->input('email'),
            'website' => $request->input('website'),
            'evacuation_option' => $request->input('evacuation_option'),
            'medical_support_website' => $request->input('medical_support_website'),
            'inpatient_services' => $request->input('inpatient_services'),
            'outpatient_services' => $request->input('outpatient_services'),
            'hour_emergency_services' => $request->input('hour_emergency_services'),
            'ambulance' => $request->input('ambulance'),
            'helipad' => $request->input('helipad'),
            'comments' => $request->input('comments'),
            'icu' => $request->input('icu'),
            'medical' => $request->input('medical'),
            'pediatric' => $request->input('pediatric'),
            'maternal' => $request->input('maternal'),
            'dental' => $request->input('dental'),
            'optical' => $request->input('optical'),
            'ioc' => $request->input('ioc'),
            'laboratory' => $request->input('laboratory'),
            'pharmacy' => $request->input('pharmacy'),
            'medical_imaging' => $request->input('medical_imaging'),
            'medical_student_training' => $request->input('medical_student_training'),
            'medical_personel_disclaimer' => $request->input('medical_personel_disclaimer'),
            'doctors' => $request->input('doctors'),
            'nurses' => $request->input('nurses'),
            'dental_therapist' => $request->input('dental_therapist'),
            'laboratory_assistants' => $request->input('laboratory_assistants'),
            'community_health' => $request->input('community_health'),
            'health_inspectors' => $request->input('health_inspectors'),
            'malaria_control_officers' => $request->input('malaria_control_officers'),
            'health_extention_officers' => $request->input('health_extention_officers'),
            'casuals' => $request->input('casuals'),
            'others' => $request->input('others'),
            'nearest_airfield' => $request->input('nearest_airfield'),
            'nearest_police_station' => $request->input('nearest_police_station'),
            'nearest_accommodation' => $request->input('nearest_accommodation'),
            'travel_agent' => $request->input('travel_agent'),
            'other_medical_info' => $request->input('other_medical_info'),
            'icon' => $request->input('icon'),
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('image/hospital', 'public');

            // Hapus gambar lama jika ada
            if ($hospital->image && Storage::disk('public')->exists($hospital->image)) {
                Storage::disk('public')->delete($hospital->image);
            }

             $data['image'] = 'storage/'.$path;

        }

         $hospital->update($data);
          // Redirect dengan pesan sukses
        return redirect()->route('hospitaldata.index')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Hospital::findOrFail($id);

        if($role->delete()){
            $response['success'] = 1;
            $response['msg'] = 'Delete successfully';
        }else{
            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response);
    }

     public function toggleStatus($id)
    {
        $hospital = Hospital::findOrFail($id);
        $hospital->hospital_status = $hospital->hospital_status ? 0 : 1; // toggle
        $hospital->save();

        return response()->json([
            'success' => true,
            'status' => $hospital->hospital_status
        ]);
    }
}
