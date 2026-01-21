<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Embassiees;
use App\Models\Provincesregion;
use App\Models\City;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MasterembessyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         if(request()->ajax()) {
            return datatables()->of(Embassiees::select('*')->orderBy('id', 'desc'))
            ->addColumn('created_at', function ($row) {
                // Format tanggal jadi dd-mm-yyyy HH:MM
                return Carbon::parse($row->created_at)->format('Y-m-d H:i:s');
            })
            ->addColumn('action', function($row){
                 $updateButton = '<a href="' . route('embessydata.edit', $row->id) . '" class="btn btn-primary btn-sm">Edit</a>';
                 $deleteButton = '<button class="btn btn-sm btn-danger delete-btn" data-id="'.$row->id.'">Delete</button>';

                  if ($row->embassy_status) {
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
        return view('pages.master.embessy');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Provincesregion::orderByRaw('LOWER(provinces_region) ASC')->get();
        return view('pages.master.createembessy', [
            'provinces' => $provinces
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $embassy = new Embassiees();

         if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $randomName = Str::random(40) . '.' . $extension;

            // Simpan di folder 'images' di disk 'public'
            $path = $request->file('image')->storeAs('image/embassy', $randomName, 'public');

            // Simpan path ke database
            $embassy->image = 'storage/'.$path;

         }

        $embassy->province_id = $request->input('province_id');
        $embassy->city_id = $request->input('city');
        $embassy->name_embassiees = $request->input('embassy_name');
        $embassy->location = $request->input('location');
        $embassy->telephone = $request->input('telephone');
        $embassy->fax = $request->input('fax');
        $embassy->email = $request->input('email');
        $embassy->website = $request->input('website');
        $embassy->latitude = $request->input('latitude');
        $embassy->longitude = $request->input('longitude');

        $embassy->save();
        return redirect()->route('embessydata.index')->with('success', 'Data Succesfully Save');
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
        $embassy = Embassiees::findOrFail($id);
        $provinces = Provincesregion::orderByRaw('LOWER(provinces_region) ASC')->get();
        $cities = City::orderByRaw('LOWER(city) ASC')->get();
        return view('pages.master.editembassy', [
            'embassy' => $embassy,
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
        $embassy = Embassiees::findOrFail($id);

         // Update data
        $data = [
            'province_id' => $request->input('province_id'),
            'city_id' => $request->input('city'),
            'name_embassiees' => $request->input('embassy_name'),
            'location' => $request->input('location'),
            'telephone' => $request->input('telephone'),
            'fax' => $request->input('fax'),
            'email' => $request->input('email'),
            'website' => $request->input('website'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
        ];

         if ($request->hasFile('image')) {
            $path = $request->file('image')->store('image/embassy', 'public');

            // Hapus gambar lama jika ada
            if ($embassy->image && Storage::disk('public')->exists($embassy->image)) {
                Storage::disk('public')->delete($embassy->image);
            }

            $data['image'] = 'storage/'.$path;

        }

         $embassy->update($data);

         // Redirect dengan pesan sukses
        return redirect()->route('embessydata.index')->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Embassiees::findOrFail($id);

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
        $embassy = Embassiees::findOrFail($id);
        $embassy->embassy_status = $embassy->embassy_status ? 0 : 1; // toggle
        $embassy->save();

        return response()->json([
            'success' => true,
            'status' => $embassy->embassy_status
        ]);
    }

    public function getCities($province_id)
    {
        $cities = City::where('province_id', $province_id)->get();
        return response()->json($cities);
    }
}
