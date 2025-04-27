@extends('admin.layout.layout-admin')
@section('content')
<div class="contains">
    <h1 id="page">Validation des Ventes</h1>
    <div class="table-container2 lg">
        <table>
            <thead>
                <tr>
                    <th>RANG</th>
                    <th>UTILISATEUR</th>
                    <th>CRYPTO</th>
                    <th>COURS</th>
                    <th>NOMBRE</th>
                    <th>DATE DE VENTE</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventes as $index => $vente)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $vente->user_id }}</td>
                        <td>{{ $vente->crypto_name }}</td>
                        <td>{{ number_format($vente->cours, 2, ',', ' ') }} USD</td>
                        <td>{{ $vente->nombre }}</td>
                        <td>{{ \Carbon\Carbon::parse($vente->date_mouvement)->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('ventes.valider', $vente->id_mouvement_crypto) }}" method="POST">
                                @csrf
                                @method('POST') <!-- Vous pouvez utiliser 'PUT' si vous souhaitez une méthode PUT -->
                                <button type="submit" class="glitch-btn"  style="padding: 5px 5px;">Valider</button>
                            </form>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
