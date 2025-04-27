<?php

namespace App\Http\Controllers;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;
use GuzzleHttp\Client;

class FirebaseUserController extends Controller
{
    public function getProfileImage($userId)
    {
        $keyFilePath = base_path('projet-cloud-final-firebase-adminsdk-fbsvc-d8ca8a2b3f.json');
        $scopes = ['https://www.googleapis.com/auth/datastore'];

        $credentials = new ServiceAccountCredentials($scopes, $keyFilePath);

        $accessToken = $credentials->fetchAuthToken(HttpHandlerFactory::build())['access_token'];

        $client = new Client();

        $url = "https://firestore.googleapis.com/v1/projects/projet-cloud-final/databases/(default)/documents/users/{$userId}";

        $headers = [
            'Authorization' => "Bearer {$accessToken}",
            'Accept' => 'application/json',
        ];

        try {
            $response = $client->get($url, ['headers' => $headers]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['fields']['profileImage']['stringValue'])) {
                return response()->json([
                    'status' => 'success',
                    'profileImage' => $data['fields']['profileImage']['stringValue'],
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Image de profil non trouvée.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la récupération des données : ' . $e->getMessage(),
            ], 500);
        }
    }
}
