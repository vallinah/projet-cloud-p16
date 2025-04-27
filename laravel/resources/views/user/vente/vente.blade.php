@extends('user.layout.layout-user')

@section('content')
<div class="contains">
    <h1 id="page">Historique des ventes et achats</h1>

    <div class="classement">
        <table>
            <thead>
                <tr>
                <th>user</th>                    
                <th>Crypto</th>
                <th>Nombre</th>
                <th>Cours</th>
                <th>Type</th>
                <th>Date</th>
                <th>validite</th>
                </tr>
            </thead>
            <tbody class="crypto-tbody">
                @foreach ($history as $movement)
                    <tr>
                        <td>{{ $movement->user_id }}</td>
                        <td>{{ $movement->cryptocurrency->name ?? 'N/A' }}</td>
                        <td>{{ $movement->nombre }}</td>
                        <td>${{ number_format($movement->cours, 2) }}</td>
                        <td>{{ $movement->vente ? 'Vente' : 'Achat' }}</td>
                        <td>{{ $movement->date_mouvement }}</td>
                        <td>{{ $movement->is_valid ? 'oui' : 'non' }}</td>
                    </tr>
                @endforeach    
            </tbody>
        </table>
    </div>

</div>
@endsection
