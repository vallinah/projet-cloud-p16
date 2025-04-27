@extends('user.layout.layout-user')  <!-- Extends le layout principal -->

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Historique de l'utilisateur {{ $userId }}</h2>
        </div>
        <div class="card-body">
            <table class="table-container lg">
                <thead>
                    <tr>
                        <th>Crypto</th>
                        <th>Nombre</th>
                        <th>Cours</th>
                        <th>Type</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($history as $movement)
                        <tr>
                            <td>{{ $movement->cryptocurrency->name ?? 'N/A' }}</td>
                            <td>{{ $movement->nombre }}</td>
                            <td>${{ number_format($movement->cours, 2) }}</td>
                            <td>{{ $movement->vente ? 'Vente' : 'Achat' }}</td>
                            <td>{{ $movement->date_mouvement }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>       
        </div>
    </div>
</div>
@endsection
