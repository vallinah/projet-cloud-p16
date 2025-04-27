<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use DateTime;
use DateTimeZone;

class AuthController extends Controller
{
    protected $apiUrl = 'http://dotnet-api/api';

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $loginResponse = Http::post($this->apiUrl . '/Users/login', [
                'login' => $validated['email'],
                'password' => $validated['password']
            ]);

            $loginData = $loginResponse->json();

            if ($loginResponse->successful() && isset($loginData['status']) && $loginData['status'] == 'success') {
                $userResponse = Http::get($this->apiUrl . '/Users/get-by-email', [
                    'email' => $validated['email']
                ]);

                $userData = $userResponse->json();

                if ($userResponse->successful() && $userData['status'] == 'success') {
                    session(['temp_user_id' => $userData['data']['userId']]);
                    return redirect()->route('pin-code');
                }

                return back()->withErrors(['email' => 'Erreur lors de la récupération des informations utilisateur']);
            }

            if ($loginResponse->failed()) {
                return back()->withErrors(['email' => $loginData['message']]);
            }

            return back()->withErrors(['email' => 'Une erreur s\'est produite']);

        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Une erreur inattendue s\'est produite']);
        }
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:4',
            'dateOfBirth' => 'required|date',
        ]);

        $validated['dateOfBirth'] = (new DateTime($validated['dateOfBirth']))
        ->setTimezone(new DateTimeZone('UTC'))
        ->format('Y-m-d\TH:i:s\Z');


        $response = Http::post($this->apiUrl . '/Users/register', $validated);
        $data = $response->json();
       ///   dd($data);
        if ($response->successful() && isset($data['status']) && $data['status'] === 'success') {
            $request->session()->put('email', $validated['email']);
       
            return redirect()->route('validate-registration')->with('status', $data['message']);
        }

        if (isset($data['status']) && $data['status'] === 'error' && isset($data['message'])) {
            return back()->withErrors([$data['message']]);
        }

        return back()->withErrors(['email' => 'Une erreur s\'est produite.']);
    }

    public function checkPinCode(Request $request)
    {
        try {
            $userId = session('temp_user_id');

            if (!$userId) {
                return redirect()->route('login')
                    ->withErrors(['email' => 'Session expirée, veuillez vous reconnecter']);
            }

            $validated = $request->validate([
                'pinCode' => 'required'
            ]);

            $response = Http::post($this->apiUrl . '/Users/check_pin_code', [
                'userId' => $userId,
                'pinCode' => $validated['pinCode']
            ]);

            $data = $response->json();

            if ($response->successful()) {
                if ($data['status'] == 'success') {
                    $user = User::where('user_id', $userId)->first();

                    if (!$user) {
                        return back()->withErrors(['pinCode' => 'Utilisateur non trouvé']);
                    }
                    Auth::login($user);

                    session()->forget('temp_user_id');

                    return redirect()->route('home')
                        ->with('success', 'Authentification réussie !');
                } else {
                    $errorMessage = $data['message'] ?? 'Code PIN invalide';
                    return back()->withErrors(['pinCode' => $errorMessage]);
                }
            } else {
                $errorMessage = "Erreur HTTP " . $response->status() . ": " . ($data['message'] ?? 'Erreur de communication avec le serveur');
                return back()->withErrors(['pinCode' => $errorMessage]);
            }

        } catch (\Exception $e) {
            return back()->withErrors([
                'pinCode' => 'Erreur: ' . $e->getMessage()
            ]);
        }
    }
    
    public function validateRegistration(Request $request)
    {
        $validated = $request->validate([
            'pinCode' => 'required',
        ]);
        
        try {
            $email = $request->session()->get('email');
            if (!$email) {
                return back()->withErrors(['email' => 'Aucune adresse email trouvée en session. Veuillez recommencer l\'inscription.']);
            }
    
            $payload = [
                'Email' => $email,
                'PinCode' => $validated['pinCode']
            ];
    
            $response = Http::post($this->apiUrl . '/Users/validate_inscription', $payload);
            $data = $response->json();
    
            if ($response->successful() && $data['status'] == 'success') {
                session(['user_id' => $data['userId']]);
                Auth::loginUsingId($data['userId']);
                return redirect()->route('home')->with('success', 'Inscription réussie. Bienvenue à vous !');
            }
    
            if ($response->failed()) {
                return back()->withErrors(['api' => $data['message'] ?? 'Une erreur s\'est produite.']);
            }
    
            return back()->withErrors(['email' => $data['message'] ?? 'Une erreur s\'est produite.']);
        } catch (\Exception $e) {
            return back()->withErrors(['api' => 'Une erreur de connexion s\'est produite.']);
        }
    }


}