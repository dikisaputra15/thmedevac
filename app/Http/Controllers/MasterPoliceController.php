<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Police;
use App\Models\Provincesregion;
use App\Models\City;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MasterPoliceController extends Controller
{
     public function index()
    {
         if(request()->ajax()) {

            $data = Police::query();

            return datatables()->of($data)

            ->editColumn('created_at', function ($row) {
                return $row->created_at
                    ? Carbon::parse($row->created_at)->format('Y-m-d H:i:s')
                    : '-';
            })

            ->editColumn('updated_at', function ($row) {
                return $row->updated_at
                    ? Carbon::parse($row->updated_at)->format('Y-m-d H:i:s')
                    : '-';
            })

            ->addColumn('action', function($row){

                $updateButton = '<a href="' . route('policedata.edit', $row->id) . '" class="btn btn-primary btn-sm">Edit</a>';

                $deleteButton = '<button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'">Delete</button>';

                if ($row->police_status) {
                    $statusButton = '<button class="btn btn-sm btn-warning status-btn" data-id="'.$row->id.'">Unpublish</button>';
                } else {
                    $statusButton = '<button class="btn btn-sm btn-success status-btn" data-id="'.$row->id.'">Publish</button>';
                }

                return $updateButton." ".$deleteButton." ".$statusButton;
            })

            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }

        return view('pages.master.police');
    }

     public function create()
    {
         $provinces = Provincesregion::all();
        return view('pages.master.createpolice', [
            'provinces' => $provinces
        ]);
    }

    public function store(Request $request)
    {
        $police = new Police();


        $police->province_id = $request->input('province_id');
        $police->city_id = $request->input('city');
        $police->name_police = $request->input('name_police');
        $police->classification = $request->input('classification');
        $police->level = $request->input('level');
        $police->category = $request->input('category');
        $police->location = $request->input('location');
        $police->telephone = $request->input('telephone');
        $police->fax = $request->input('fax');
        $police->email = $request->input('email');
        $police->website = $request->input('website');
        $police->hrs_of_operation = $request->input('hrs_of_operation');
        $police->latitude = $request->input('latitude');
        $police->longitude = $request->input('longitude');
        $police->icon = $request->input('icon');
        $police->created_at = Carbon::now();
        $police->updated_by = auth()->user()->name;

        $police->save();
        return redirect()->route('policedata.index')->with('success', 'Data Succesfully Save');
    }

     public function edit($id)
    {
        $police = Police::findOrFail($id);
        $provinces = Provincesregion::all();
        $cities = City::all();
        return view('pages.master.editpolice', [
            'police' => $police,
            'provinces' => $provinces,
            'cities' => $cities
        ]);
    }

     public function update(Request $request, $id)
    {
        // Cari data airport berdasarkan ID
        $police = Police::findOrFail($id);

         // Update data
        $data = [
            'province_id' => $request->input('province_id'),
            'city_id' => $request->input('city'),
            'name_police' => $request->input('name_police'),
            'classification' => $request->input('classification'),
            'level' => $request->input('level'),
            'category' => $request->input('category'),
            'location' => $request->input('location'),
            'telephone' => $request->input('telephone'),
            'fax' => $request->input('fax'),
            'email' => $request->input('email'),
            'website' => $request->input('website'),
            'hrs_of_operation' => $request->input('hrs_of_operation'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'icon' => $request->input('icon'),
            'updated_at' => Carbon::now(),
            'updated_by' => auth()->user()->name,
        ];

         $police->update($data);

         // Redirect dengan pesan sukses
        return redirect()->route('policedata.index')->with('success', 'Data berhasil diupdate');
    }

     public function destroy($id)
    {
        $role = Police::findOrFail($id);

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
        $police = Police::findOrFail($id);
        $police->police_status = $police->police_status ? 0 : 1; // toggle
        $police->save();

        return response()->json([
            'success' => true,
            'status' => $police->police_status
        ]);
    }
}
