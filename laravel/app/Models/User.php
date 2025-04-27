<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;
use GuzzleHttp\Client;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'date_of_birth',
        'is_valid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'created_date' => 'datetime',
        'is_valid' => 'boolean',
    ];

    public function cryptos()
    {
        return $this->belongsToMany(Cryptocurrency::class, 'user_crypto', 'user_id', 'crypto_id')
            ->withPivot('quantity');
    }

     /**
     * Récupère l'image de profil depuis Firebase
     *
     * @return string|null
     */
    public function getProfileImage()
    {
        $userId = $this->user_id;

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
                return $data['fields']['profileImage']['stringValue'];
            }

            return null;
        } catch (\Exception $e) {
            return null; 
        }
    }
}