<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AchatController;
use App\Http\Controllers\DepotController;
use App\Http\Controllers\RetraitController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\MouvementFondController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\FirebaseInsertionController;
use App\Http\Controllers\FondInsertionController;


// Route::get('/', function () {
//     return view('user.auth.login');
// });
Route::get('/', function () {
    return view('guest.index');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.auth.login');
    })->name('admin');
    Route::post('/admin', [AdminController::class, 'login'])->name('admin');

    Route::get('/login', function () {
        return view('user.auth.login');
    })->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', function () {
        return view('user.auth.register');
    })->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/validate-registration', function () {
        return view('user.auth.validate-register');
    })->name('user.validate-register');
    Route::post('/validate-registration', [AuthController::class, 'validateRegistration'])->name('validate-registration');

    Route::get('/pin-code', function () {
        return view('user.auth.pin-code');
    })->name('pin-code');
    Route::post('/check-pin-code', [AuthController::class, 'checkPinCode'])->name('check-pin-code');

    Route::get('/admin/ventes', [VenteController::class, 'index'])->name('ventes');
    Route::post('/admin/ventes/valider/{id}', [VenteController::class, 'validerVente'])->name('ventes.valider');

    Route::get('/admin/achats', [AchatController::class, 'index'])->name('achats');
    Route::post('/admin/achats/valider/{id}', [AchatController::class, 'validerAchat'])->name('achats.valider');


    Route::get('/admin/depots', [DepotController::class, 'index'])->name('depots');
    Route::post('/admin/depots/valider/{id}', [DepotController::class, 'validerDepot'])->name('depots.valider');

    Route::get('/admin/retraits', [RetraitController::class, 'index'])->name('retraits');
    Route::post('/admin/retraits/valider/{id}', [RetraitController::class, 'validerRetrait'])->name('retraits.valider');

    Route::get('/admin/operations', [OperationController::class, 'index'])->name('operations');
    Route::get('/admin/operations/user/{id}', [OperationController::class, 'get_by_id'])->name('operations.user');


});


Route::middleware(['auth'])->group(function () {
    Route::get('/user', function () {
        return view('user.acceuil.home', ['title' => 'Acceuil']);
    })->name('home');
    Route::get('/crypto/prices', function () {
        return view('crypto/prices');
    })->name('crypto.prices');

    Route::get('api/crypto/prices', [App\Http\Controllers\Api\CryptoPriceController::class, 'getCurrentPrices']);

    Route::get('/profile', [ProfileController::class, 'get_profil_user_by_Id'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/filtre', [PortfolioController::class, 'filtre'])->name('filtre');
    Route::post('/filtrevalidate', [PortfolioController::class, 'filtreuser'])->name('user.filtre');
    Route::get('/portfolio-data', [PortfolioController::class, 'getPortfolioData']);

    //Route::get('/firebase/profile-image/{userId}', [FirebaseUserController::class, 'getProfileImage']);

    Route::get('/portofilo', [PortfolioController::class, 'index'])->name('portofilo');
    Route::get('/insert-firebase-data', [FirebaseInsertionController::class, 'insertAllData']);
    Route::get('/insert-fond-data', [FondInsertionController::class, 'insertAllData']);

    Route::get('/fond', [MouvementFondController::class, 'getFond'])->name('fond');
    Route::post('/store', [MouvementFondController::class, 'store'])->name('store');

    Route::get('/analysis', [AnalysisController::class, 'showAnalysisForm'])->name('analysis.form');
    Route::post('/analysis', [AnalysisController::class, 'analyze'])->name('analysis.analyze');

    Route::prefix('wallet')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('user.wallet.index');
        Route::get('/deposit', [WalletController::class, 'deposit'])->name('deposit');
        Route::post('/withdraw', [WalletController::class, 'withdraw'])->name('withdraw');
        Route::get('/validate/{code}', [WalletController::class, 'validateTransaction'])->name('validate');
    });

    // commission

    Route::get('/commission', [CommissionController::class, 'index'])->name('commission');
    Route::put('/commission/{id}/update', [CommissionController::class, 'update'])->name('commission.update');
    Route::get('/commission-analyse', [CommissionController::class, 'showAnalysCommission'])->name('commission.analyse');
    Route::post('/commission-validate', [CommissionController::class, 'analyseCommission'])->name('commission.validanalys');

    // vente 

    Route::get('/evolution', [VenteController::class, 'evolution'])->name('evolution');
    Route::get('/evolution-prices/{crypto_id}', [VenteController::class, 'getHistory'])->name('evolution-prices');
    Route::post('/evolution-check', [VenteController::class, 'evolutionCrypto'])->name('evolution-check');
    Route::post('/sell', [VenteController::class, 'sellCrypto'])->name('vente.sellCrypto');
    Route::get('/userhistory', [VenteController::class, 'historiquevente'])->name('userhistory');


    // firebase 

 //   Route::get('/test-firebase', [FirebaseController::class, 'testFirebase']);

    Route::get('/logout', function () {

        session()->flush();
        return redirect()->route('login');
    })->name('logout');
});