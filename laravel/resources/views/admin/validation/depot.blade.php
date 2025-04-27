@extends('admin.layout.layout-admin')

@section('content')
<div class="contains">
    <h1 id="page">Validation des Dépôts <span>Mis à jour le {{ now()->format('H:i') }}</span></h1>
    <div class="table-container2 lg">
        <table>
            <thead>
                <tr>
                    <th>RANG</th>
                    <th>UTILISATEUR</th>
                    <th>MONTANT</th>
                    <th>DATE DE DÉPÔT</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($depots as $index => $depot)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $depot->user_id }}</td>
                        <td>{{ number_format($depot->montant_depot, 2, ',', ' ') }} USD</td>
                        <td>{{ \Carbon\Carbon::parse($depot->date_mouvement_fond)->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('depots.valider', $depot->id_mouvement_fond) }}" method="POST">
                                @csrf
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
