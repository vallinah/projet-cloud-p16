<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Admin;
use Laravel\Pail\ValueObjects\Origin\Console;

class AdminController extends Controller
{
    protected $apiUrl = 'http://dotnet-api/api';

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'login' => 'required',
                'password' => 'required'
            ]);

            $loginResponse = Http::post($this->apiUrl . '/Admin/login', [
                'login' => $validated['login'],
                'password' => $validated['password']
            ]);

            $loginData = $loginResponse->json();
            // dump('loginData azo');

            if ($loginResponse->successful()) {
                // dump($loginData); // Affiche les données de réponse de l'API
                if (isset($loginData['status']) && $loginData['status'] == 'success') {
                    // dump('session');

                    $admin = Admin::find(id: $loginData['data']['adminId']);
                    if ($admin) {
                        Auth::login($admin); 
                        session(['user_id' => $admin]);
                        // dump(Auth::user()); // Affiche l'utilisateur connecté
                        return redirect()->route('ventes')->with('success', 'Authentification réussie !');

                    }
                }

            }

            if ($loginResponse->failed()) {
                // dump('loginData faild');
                return back()->withErrors(['admin' => $loginData['message']]);
            }


        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Une erreur inattendue s\'est produite']);
        }
    }
}