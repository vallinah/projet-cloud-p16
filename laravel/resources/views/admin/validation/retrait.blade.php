@extends('admin.layout.layout-admin')

@section('content')
<div class="contains">
    <h1 id="page">Validation des retraits</h1>
    <div class="table-container2 lg">
        <table>
            <thead>
                <tr>
                    <th>RANG</th>
                    <th>UTILISATEUR</th>
                    <th>MONTANT</th>
                    <th>DATE DE RETRAIT</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($retraits as $index => $retrait)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $retrait->user_id }}</td>
                        <td>{{ number_format($retrait->montant_retrait, 2, ',', ' ') }} USD</td>
                        <td>{{ \Carbon\Carbon::parse($retrait->date_mouvement_fond)->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('retraits.valider', $retrait->id_mouvement_fond) }}" method="POST">
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
