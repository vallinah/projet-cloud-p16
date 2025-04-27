@extends('user.layout.layout-user')

@section('content')
<div class="contains">
    <h1 id="page">List user </h1>
    <div class="table-container  lg">
        <table>
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Total Achat</th>
                    <th>Total Vente</th>
                    <th>Portefeuille</th>
                </tr>
            </thead>
            <tbody class="crypto-tbody">
                    @foreach ($results as $result)
                        <tr>
                            <td>{{ $result->user_id }}</td>
                            <td>${{ number_format($result->total_achat, 2) }}</td>
                            <td>${{ number_format($result->total_vente, 2) }}</td>
                            <td>${{ number_format($result->portefeuille, 2) }}</td>
                        </tr>
                     @endforeach
            </tbody>
        </table>

    </div>
</div>
@endsection
