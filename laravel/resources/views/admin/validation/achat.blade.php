@extends('admin.layout.layout-admin')
@section('content')
<div class="contains">
    <h1 id="page">Validation des achats</h1>
    <div class="table-container2 lg">
        <table>
            <thead>
                <tr>
                    <th>RANG</th>
                    <th>UTILISATEUR</th>
                    <th>CRYPTO</th>
                    <th>COURS</th>
                    <th>NOMBRE</th>
                    <th>DATE D' ACHAT</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($achats as $index => $achat)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $achat->user_id }}</td>
                        <td>{{ $achat->crypto_name }}</td>
                        <td>{{ number_format($achat->cours, 2, ',', ' ') }} USD</td>
                        <td>{{ $achat->nombre }}</td>
                        <td>{{ \Carbon\Carbon::parse($achat->date_mouvement)->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('achats.valider', $achat->id_mouvement_crypto) }}" method="POST">
                                @csrf
                                @method('POST') <!-- Vous pouvez utiliser 'PUT' si vous souhaitez une méthode PUT -->
                                <button type="submit" class="glitch-btn" style="padding: 5px 5px;">Valider</button>

                            </form>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
