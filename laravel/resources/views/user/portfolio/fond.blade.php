@extends('user.layout.layout-user')

@section('content')
<div class="contains">
    <h1 id="page">Gestion fond</h1>
    <div class="dash">
        <div class="card" style="background-color: rgb(0, 255, 255);">
            <h2>Total retrait</h2>
            <p id="total-market-cap">{{ number_format($mouvement['total_retrait'], 2) }}<span></span></p>
        </div>
        <div class="card" style="background-color:#ffd500;">
            <h2>Total depot</h2>
            <p id="total-market-cap">{{ number_format($mouvement['total_depot'], 2) }}<span></span></p>
        </div>
        <div class="card" style="background-color: #fff;">
            <h2>Total solde</h2>
            <p id="total-market-cap">{{ number_format($mouvement['total_fond'], 2) }}<span></span></p>
        </div>
    </div>
    <div class="table-container  lg">
        <table>
            <thead>
                <tr>
                    <th>Date du mouvement</th>
                    <th>Retrait</th>
                    <th>Dépôt</th>
                    <th>Validé</th>
                </tr>
            </thead>
            <tbody class="crypto-tbody">
                @foreach ($histomouvements as $result)
                    <tr>
                        <td>{{ $result['date_mouvement_fond'] }}</td>
                        <td>{{ number_format($result['montant_retrait'], 2) }}</td>
                        <td>{{ number_format($result['montant_depot'], 2) }}</td>
                        <td>{{ $result['is_valid'] ? 'Oui' : 'Non' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="form-container sm">
        <h2>Depot et Retrait</h2>
        <form method="POST" action="{{ route('store') }}">
            @csrf
            <div class="form-control">
                <label for="montant">Montant</label>
                <input type="number" name="montant" id="montant" class="form-control" required>
            </div>
            <div class="form-control">
                <label for="type">Type mouvement</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="depot">Dépôt</option>
                    <option value="retrait">Retrait</option>
                </select>
            </div>
            <div class="button-div">
                <button type="submit" class="achat-btn">Valider</button>
            </div>
        </form>
    </div>

</div>
@endsection
