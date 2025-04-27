<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Factory;

class FirebaseInsertionController extends Controller
{
    private $database;

    public function __construct()
    {
        // Initialisation de Firebase
        $this->database = (new Factory)
            ->withServiceAccount(base_path('projet-cloud-final-firebase-adminsdk-fbsvc-d8ca8a2b3f.json'))
            ->withDatabaseUri('https://projet-cloud-final-default-rtdb.firebaseio.com/')
            ->createDatabase();
    }

    public function insertAllData()
    {
        $data = [
            ['nombre' => 0.5, 'cours' => 300, 'vente' => 1, 'achat' => 0, 'crypto_id' => 'CRY-00001', 'user_id' => 'USE-00001', 'date_mouvement' => '2025-02-06 10:00:00', 'is_valid' => false],
            ['nombre' => 2, 'cours' => 245, 'vente' => 0, 'achat' => 1, 'crypto_id' => 'CRY-00002', 'user_id' => 'USE-00001', 'date_mouvement' => '2025-02-04 11:30:00', 'is_valid' => true],
            ['nombre' => 1.3, 'cours' => 200, 'vente' => 0, 'achat' => 1, 'crypto_id' => 'CRY-00001', 'user_id' => 'USE-00001', 'date_mouvement' => '2025-02-04 11:30:00', 'is_valid' => true],
            ['nombre' => 1, 'cours' => 450, 'vente' => 0, 'achat' => 1, 'crypto_id' => 'CRY-00001', 'user_id' => 'USE-00002', 'date_mouvement' => '2025-02-06 12:45:00', 'is_valid' => true],
            ['nombre' => 3, 'cours' => 735, 'vente' => 0, 'achat' => 1, 'crypto_id' => 'CRY-00002', 'user_id' => 'USE-00002', 'date_mouvement' => '2025-02-06 14:00:00', 'is_valid' => true],
            ['nombre' => 2, 'cours' => 300, 'vente' => 0, 'achat' => 1, 'crypto_id' => 'CRY-00002', 'user_id' => 'USE-00002', 'date_mouvement' => '2025-02-06 15:15:00', 'is_valid' => false],
            ['nombre' => 0.2, 'cours' => 300, 'vente' => 1, 'achat' => 0, 'crypto_id' => 'CRY-00001', 'user_id' => 'USE-00003', 'date_mouvement' => '2025-02-07 09:30:00', 'is_valid' => true],
            ['nombre' => 3, 'cours' => 245, 'vente' => 0, 'achat' => 1, 'crypto_id' => 'CRY-00002', 'user_id' => 'USE-00003', 'date_mouvement' => '2025-02-07 10:00:00', 'is_valid' => true],
            ['nombre' => 1.5, 'cours' => 200, 'vente' => 0, 'achat' => 1, 'crypto_id' => 'CRY-00001', 'user_id' => 'USE-00004', 'date_mouvement' => '2025-02-07 14:45:00', 'is_valid' => true],
            ['nombre' => 2, 'cours' => 450, 'vente' => 0, 'achat' => 1, 'crypto_id' => 'CRY-00001', 'user_id' => 'USE-00004', 'date_mouvement' => '2025-02-07 15:00:00', 'is_valid' => true],
            ['nombre' => 2, 'cours' => 735, 'vente' => 0, 'achat' => 1, 'crypto_id' => 'CRY-00002', 'user_id' => 'USE-00005', 'date_mouvement' => '2025-02-07 18:30:00', 'is_valid' => false],
            ['nombre' => 0.5, 'cours' => 300, 'vente' => 1, 'achat' => 0, 'crypto_id' => 'CRY-00002', 'user_id' => 'USE-00006', 'date_mouvement' => '2025-02-07 19:00:00', 'is_valid' => false],
            ['nombre' => 1, 'cours' => 245, 'vente' => 0, 'achat' => 1, 'crypto_id' => 'CRY-00002', 'user_id' => 'USE-00006', 'date_mouvement' => '2025-02-07 20:30:00', 'is_valid' => true],
            ['nombre' => 0.5, 'cours' => 200, 'vente' => 0, 'achat' => 1, 'crypto_id' => 'CRY-00001', 'user_id' => 'USE-00007', 'date_mouvement' => '2025-02-07 12:00:00', 'is_valid' => true],
            ['nombre' => 1, 'cours' => 450, 'vente' => 0, 'achat' => 1, 'crypto_id' => 'CRY-00001', 'user_id' => 'USE-00007', 'date_mouvement' => '2025-02-07 13:00:00', 'is_valid' => true],
            ['nombre' => 3, 'cours' => 300, 'vente' => 0, 'achat' => 1, 'crypto_id' => 'CRY-00002', 'user_id' => 'USE-00007', 'date_mouvement' => '2025-02-07 13:45:00', 'is_valid' => false],
        ];

        DB::beginTransaction();

        try {
            // Insertion des données dans la base de données locale
    //        DB::table('mouvement_crypto')->insert($data);

            // Insertion dans Firebase
            foreach ($data as $entry) {
                $this->database
                    ->getReference('mouvement_crypto/' . $entry['user_id'])
                    ->push($entry);
            }

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Données insérées avec succès.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
