<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    protected $apiUrl = 'http://dotnet-api/api';

    public function get_profil_user_by_Id()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'Veuillez vous connecter']);
        }

        $userResponse = Http::get($this->apiUrl . '/Users/update/' . $userId);

        $userData = $userResponse->json();

        if ($userResponse->successful() && isset($userData['status']) && $userData['status'] == 'success') {
            return view('user.profil.edit', ['user' => $userData['data']]);
        }

        return back()->withErrors(['error' => 'Erreur lors de la récupération des informations utilisateur']);
    }

    public function update(Request $request)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['error' => 'Veuillez vous connecter']);
        }


        $validated = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
        ]);

        try {
            $response = Http::put($this->apiUrl . '/Users/update', [
                'userId' => $userId,
                'firstName' => $validated['firstName'],
                'lastName' => $validated['lastName'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ]);

            $data = $response->json();

            if ($response->successful() && $data['status'] == 'success') {
                return redirect()->route('profile.edit')->with('success', 'Profil mis à jour avec succès');
            }

            return back()->withErrors(['error' => $data['message'] ?? 'Une erreur s\'est produite']);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur inattendue : ' . $e->getMessage()]);
        }
    }

}
