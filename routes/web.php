<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\{
    HospitalController,
    EmbassieesController,
    AirportsController,
    AircharterController,
    MasterairportController,
    MasterhospitalController,
    MasterembessyController,
    MasteraircharterController,
    RoleController,
    UserController,
    HomeController
};

use App\Models\User;
use Firebase\JWT\JWT;

Route::get('/redirect-to-wp', function (Illuminate\Http\Request $request) {
    $user = Auth::user();

    if (!$user) {
        return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
    }

    $secret = env('JWT_AUTH_SECRET_KEY', 'Chelsea123!@#');

    $payload = [
        'iss' => 'https://sg.concordcmt.com',
        'iat' => time(),
        'exp' => time() + 60, // Berlaku 1 menit
        'data' => [
            'email' => $user->email,
            'name' => $user->name,
        ]
    ];

    $token = JWT::encode($payload, $secret, 'HS256');

    // Ambil target WP (dari query parameter)
    $target = $request->query('target');

    // Validasi agar hanya dua domain ini yang boleh
    $allowedTargets = [
        'incident-tracking' => 'https://id.concordreview.com/incident-tracking-dashboard/',
        'dashboard' => 'https://id.concordreview.com/indonesia-dashboard-w900/',
    ];

    if (!isset($allowedTargets[$target])) {
        abort(403, 'Target tidak valid');
    }

    // Redirect ke WordPress dengan token
    $wpUrl = $allowedTargets[$target] . '?token=' . $token;
    return redirect()->away($wpUrl);
})->name('redirect.wp');

// ==========================
// ROUTE LOGIN + JWT AUTO LOGIN
// ==========================

Route::middleware(['web', 'jwt.login'])->group(function () {

    // Halaman login awal atau redirect
    Route::get('/', function () {
        if (Auth::check()) {
            return redirect('/home');
        }
        return view('pages.auth.login');
    });

    // =====================
    // ROUTES DENGAN AUTH WAJIB
    // =====================
    Route::middleware(['auth'])->group(function () {

        Route::get('home', [HomeController::class, 'index'])->name('home');
        Route::get('/administrator', [HomeController::class, 'administrator']);
        Route::get('exurl', [HomeController::class, 'exurl'])->name('exurl');

        // === MASTER DATA ===
        Route::resource('airportdata', MasterairportController::class);
        Route::resource('hospitaldata', MasterhospitalController::class);
        Route::resource('embessydata', MasterembessyController::class);
        Route::resource('aircharterdata', MasteraircharterController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('user', UserController::class);

        Route::post('/airportdata/{id}/toggle-status', [MasterairportController::class, 'toggleStatus'])->name('airportdata.toggleStatus');
        Route::post('/hospitaldata/{id}/toggle-status', [MasterhospitalController::class, 'toggleStatus'])->name('hospitaldata.toggleStatus');
        Route::post('/embassydata/{id}/toggle-status', [MasterembessyController::class, 'toggleStatus'])->name('embassydata.toggleStatus');

        // === FITUR APP ===
        Route::resource('hospital', HospitalController::class);
        Route::get('/hospitals/{id}', [HospitalController::class, 'showdetail']);
        Route::get('/hospitals/clinic/{id}', [HospitalController::class, 'showdetailclinic']);
        Route::get('/hospitals/emergency/{id}', [HospitalController::class, 'showdetailemergency']);

        Route::resource('embassiees', EmbassieesController::class);
        Route::get('/embassiees/{id}/detail', [EmbassieesController::class, 'showdetail']);

        Route::resource('airports', AirportsController::class);
        Route::get('/airports/{id}/detail', [AirportsController::class, 'showdetail']);
        Route::get('/airports/{id}/emergency', [AirportsController::class, 'showdetailemergency']);
        Route::get('/airports/{id}/airlinesdestination', [AirportsController::class, 'showairlinesdestination']);
        Route::get('/airports/{id}/navigation', [AirportsController::class, 'shownavigation']);

        Route::resource('aircharter', AircharterController::class);

        // === API FILTER ===
        Route::get('/api/airports', [AirportsController::class, 'filter']);
        Route::get('/api/embassy', [EmbassieesController::class, 'filter']);
        Route::get('/api/hospital', [HospitalController::class, 'filter']);

        // === USER ROLE ===
        Route::post('/user/{user}/update-role', [UserController::class, 'updateRole'])->name('user.updateRole');

        // === DEPENDENCY ===
        Route::get('/get-cities/{province_id}', [MasterembessyController::class, 'getCities']);
    });
});

Route::middleware(['web', 'jwt.login'])->group(function () {
    Route::get('/airports', [AirportsController::class, 'index']);
    Route::get('/hospital', [HospitalController::class, 'index']);
    Route::get('/embassiees', [EmbassieesController::class, 'index']);
});

