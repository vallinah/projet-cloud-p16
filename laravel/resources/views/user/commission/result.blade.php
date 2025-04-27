@extends('user.layout.layout-user')  <!-- Extends le layout principal -->

@section('content')
<div class="contains">
    <h1 id="page">Resultat Commission Cryptomonnaie</h1>
        <div class="table-container  lg">
            <table>
                        <thead>
                            <tr>
                                <th>Cryptomonnaie</th>
                                <th>Type d'analyse</th>
                                <th>Date et heure min</th>
                                <th>Date et heure max</th>
                                <th>Commission</th>
                            </tr>
                        </thead>
                        <tbody class="crypto-body">
                                <tr>
                                    <td>{{ $name }}</td>
                                    <td>{{ $analysis_type }}</td>
                                    <td>{{ \Carbon\Carbon::parse($start_datetime)->format('d/m/Y H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($end_datetime)->format('d/m/Y H:i') }}</td>
                                    <td>${{ number_format($result, 2) }}</td>
                                </tr>

                        </tbody>
                    </table>
        </div>
</div>
@endsection
