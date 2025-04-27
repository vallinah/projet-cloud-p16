<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Factory;

class FondInsertionController extends Controller
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
            ['montant_depot' => 1000000, 'date_mouvement_fond' => '2024-02-05 10:15:00', 'montant_retrait' => null, 'user_id' => 'USE-00002', 'is_valid' => true],
            ['montant_depot' => null, 'date_mouvement_fond' => '2024-02-05 12:30:00', 'montant_retrait' => 450, 'user_id' => 'USE-00002', 'is_valid' => true],
            ['montant_depot' => null, 'date_mouvement_fond' => '2024-02-05 12:30:00', 'montant_retrait' => 735, 'user_id' => 'USE-00002', 'is_valid' => true],
            ['montant_depot' => 1000000, 'date_mouvement_fond' => '2024-02-05 10:15:00', 'montant_retrait' => null, 'user_id' => 'USE-00001', 'is_valid' => true],
            ['montant_depot' => null, 'date_mouvement_fond' => '2024-02-05 12:30:00', 'montant_retrait' => 585, 'user_id' => 'USE-00001', 'is_valid' => true],
            ['montant_depot' => null, 'date_mouvement_fond' => '2024-02-05 12:30:00', 'montant_retrait' => 490, 'user_id' => 'USE-00001', 'is_valid' => true],
            ['montant_depot' => 1500000, 'date_mouvement_fond' => '2025-02-07 09:00:00', 'montant_retrait' => null, 'user_id' => 'USE-00003', 'is_valid' => true],
            ['montant_depot' => null, 'date_mouvement_fond' => '2025-02-07 11:00:00', 'montant_retrait' => 300, 'user_id' => 'USE-00003', 'is_valid' => true],
            ['montant_depot' => 2000000, 'date_mouvement_fond' => '2025-02-07 14:30:00', 'montant_retrait' => null, 'user_id' => 'USE-00004', 'is_valid' => false],
            ['montant_depot' => null, 'date_mouvement_fond' => '2025-02-07 16:45:00', 'montant_retrait' => 400, 'user_id' => 'USE-00004', 'is_valid' => true],
            ['montant_depot' => 1200000, 'date_mouvement_fond' => '2025-02-07 18:15:00', 'montant_retrait' => null, 'user_id' => 'USE-00005', 'is_valid' => false],
            ['montant_depot' => null, 'date_mouvement_fond' => '2025-02-07 20:00:00', 'montant_retrait' => 250, 'user_id' => 'USE-00005', 'is_valid' => true],
            ['montant_depot' => 3000000, 'date_mouvement_fond' => '2025-02-07 08:00:00', 'montant_retrait' => null, 'user_id' => 'USE-00006', 'is_valid' => true],
            ['montant_depot' => null, 'date_mouvement_fond' => '2025-02-07 10:30:00', 'montant_retrait' => 1500, 'user_id' => 'USE-00006', 'is_valid' => true],
            ['montant_depot' => 1700000, 'date_mouvement_fond' => '2025-02-07 12:30:00', 'montant_retrait' => null, 'user_id' => 'USE-00007', 'is_valid' => true],
            ['montant_depot' => null, 'date_mouvement_fond' => '2025-02-07 13:45:00', 'montant_retrait' => 600, 'user_id' => 'USE-00007', 'is_valid' => true],
        ];

        DB::beginTransaction();

        try {
            // Insertion des données dans la base de données locale
    //        DB::table('mouvement_fond')->insert($data);

            // Insertion dans Firebase
            foreach ($data as $entry) {
                $this->database
                    ->getReference('mouvements_fonds/' . $entry['user_id'])
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
