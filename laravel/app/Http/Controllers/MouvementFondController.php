<?php

namespace App\Http\Controllers;

use App\Models\MouvementFond;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Kreait\Firebase\Factory;

class MouvementFondController extends Controller
{
    protected $database;

    public function __construct()
    {
        // Initialisation de Firebase
        $this->database = (new Factory)
            ->withServiceAccount(base_path('projet-cloud-final-firebase-adminsdk-fbsvc-d8ca8a2b3f.json'))
            ->withDatabaseUri('https://projet-cloud-final-default-rtdb.firebaseio.com/')
            ->createDatabase();
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'montant' => 'required|numeric|min:0.01',
        'type' => 'required|in:depot,retrait',
    ]);

    $userId = Auth::id();
    $solde = $this->getSoldeDisponible($userId);

    if ($validated['type'] === 'retrait' && $validated['montant'] > $solde) {
        return response()->json([
            'message' => 'Solde insuffisant pour effectuer ce retrait.',
            'solde_actuel' => $solde
        ], 400);
    }

    $mouvement = new MouvementFond();
    $mouvement->user_id = $userId;
    $mouvement->montant_retrait = $validated['type'] === 'retrait' ? $validated['montant'] : 0;
    $mouvement->montant_depot = $validated['type'] === 'depot' ? $validated['montant'] : 0;
    $mouvement->date_mouvement_fond = now();
    $mouvement->forceFill(['is_valid' => false])->save();

    // Utiliser l'ID Laravel pour l'insertion dans Firebase
    $this->insertFondToFirebase($mouvement);

    return $this->getFond();
}


public function insertFondToFirebase($mouvement)
{
    // Créer une structure de données pour Firebase
    $fondData = [
        'montant_retrait' => $mouvement->montant_retrait,
        'montant_depot' => $mouvement->montant_depot,
        'date_mouvement_fond' => $mouvement->date_mouvement_fond,
        'is_valid' => $mouvement->is_valid,
        'user_id' => $mouvement->user_id,
        'type' => $mouvement->montant_retrait > 0 ? 'retrait' : 'depot',
        'timestamp' => now()->toDateTimeString(),
    ];

    // Utiliser l'ID Laravel pour structurer l'entrée Firebase
    $this->database->getReference('mouvements_fonds/' . $mouvement->user_id . '/' . $mouvement->id)
        ->set($fondData);
}


public function getFond()
{
    $title = "Fond"; 
    try {
        $mouvements = MouvementFond::where('user_id', Auth::id())
            ->selectRaw('SUM(montant_retrait) as total_retrait, SUM(montant_depot) as total_depot')
            ->where('is_valid', true)
            ->first();

        // Assurer que les valeurs ne sont jamais nulles
        $totalRetrait = $mouvements->total_retrait ?? 0;
        $totalDepot = $mouvements->total_depot ?? 0;
        $totalFond = $totalDepot - $totalRetrait;

        $mouvement = [
            'total_retrait' => $totalRetrait,
            'total_depot' => $totalDepot,
            'total_fond' => $totalFond
        ];

        $histomouvements = $this->getByUserId(Auth::id());

        return view('user.portfolio.fond', compact('title', 'mouvement', 'histomouvements'));

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Une erreur est survenue lors de la récupération des mouvements.',
            'data' => null,
            'erreur' => $e->getMessage()
        ], 500);
    }
}

public function getSoldeDisponible($userId)
{
    $depots = MouvementFond::where('user_id', $userId)
        ->where('is_valid', true)
        ->sum('montant_depot') ?? 0;

    $retraits = MouvementFond::where('user_id', $userId)
        ->where('is_valid', true)
        ->sum('montant_retrait') ?? 0;

    return $depots - $retraits;
}

public function getByUserId($user_id)
{
    $mouvements = MouvementFond::where('user_id', $user_id)->get();

    return $mouvements->isEmpty() ? [] : $mouvements;
}
}


