@extends('user.layout.layout-user')

@section('content')
<div class="contains">
    <h1 id="page">Analyse crypto</h1>
    @if(session('error'))
        <div class="alert alert-danger mt-4">
            {{ session('error') }}
        </div>
    @endif

    @if(isset($selectedCryptos) && $selectedCryptos->count() > 0)
        <div class="table-container lg">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Symbole</th>
                        <th>Prix actuel</th>
                        <th>Date de création</th>
                        <th>Dernière mise à jour</th>
                    </tr>
                </thead>
                <tbody id="crypto-tbody">
                    @foreach($selectedCryptos as $crypto)
                        <tr>
                            <td>{{ $crypto->crypto_id }}</td>
                            <td>{{ $crypto->name }}</td>
                            <td>{{ $crypto->symbol }}</td>
                            <td>{{ $crypto->current_price }}</td>
                            <td>{{ $crypto->created_date }}</td>
                            <td>{{ $crypto->updated_date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Afficher les résultats d'analyse --}}
        @if(isset($analysisType))
            @if(isset($analysisResult) && !empty($analysisResult))
                <div class="card mt-5">
                    <div class="card-body">
                        <p class="lead">
                            {{ $analysisType }} <strong>: {{ $analysisResult }}</strong>
                        </p>
                    </div>
                </div>
            @else
                <div class="alert alert-warning mt-4">
                    Aucun résultat pour l'analyse.
                </div>
            @endif
        @endif
    @else
        @if(request()->isMethod('post'))
            <div class="alert alert-warning mt-4">
                Aucun résultat disponible pour les cryptomonnaies sélectionnées.
            </div>
        @endif
    @endif

    <div class="form-container sm">
        <h2>Filtre d'analyse</h2>
        <form action="{{ route('analysis.analyze') }}" method="POST">
            @csrf
            <div class="form-control">
                <label for="analysis_type">Type d'analyse</label>
                <select name="analysis_type" id="analysis_type" class="form-control">
                    <option value="quartile">1er Quartile</option>
                    <option value="max">Maximum</option>
                    <option value="min">Minimum</option>
                    <option value="average">Moyenne</option>
                    <option value="standard_deviation">Écart-type</option>
                </select>
            </div>
            <div class="form-control">
                <label>Cryptomonnaies</label>
                <div class="checklist">
                    <div>
                        <label class="form-check-label" for="all_cryptos">Tous</label>
                        <input type="checkbox" class="form-check-input small-checkbox" name="cryptocurrencies[]" value="all" id="all_cryptos">
                    </div>
                    @foreach($cryptocurrencies->chunk(5) as $cryptoRow)
                        @foreach($cryptoRow as $crypto)
                            <div>
                                <label class="form-check-label" for="crypto_{{ $crypto->crypto_id }}">
                                    {{ $crypto->name }} ({{ $crypto->symbol }})
                                </label>
                                <input type="checkbox" class="form-check-input small-checkbox" name="cryptocurrencies[]"
                                    value="{{ $crypto->crypto_id }}" id="crypto_{{ $crypto->crypto_id }}">
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
            <div class="form-control">
                <label for="start_datetime">Date et heure min</label>
                <input type="datetime-local" name="start_datetime" id="start_datetime"
                        class="form-control" value="{{ old('start_datetime') }}">
            </div>
            <div class="form-control">
                <label for="end_datetime">Date et heure max</label>
                <input type="datetime-local" name="end_datetime" id="end_datetime"
                        class="form-control" value="{{ old('end_datetime') }}">
            </div>
            <div class="button-div">
                <button type="submit" class="achat-btn">Analyser</button>
            </div>
        </form>
    </div>
</div>

<style>
    .crypto-grid {
        display: flex;
        flex-direction: column;
        gap: 5px;
        width:9%;
    }
    .crypto-row {
        display: flex;
        gap: 5px;
    }
    .crypto-item {
        display: flex;
        align-items: center;
        gap: 1px;
        width: 50px;
    }
    .small-checkbox {
        width: 3px;
        height: 3px;
        min-width: 3px;
        min-height: 3px;
    }
</style>

@push('scripts')
<script>
document.getElementById('all_cryptos').addEventListener('change', function() {
    const cryptoCheckboxes = document.querySelectorAll('input[name="cryptocurrencies[]"]');
    cryptoCheckboxes.forEach(checkbox => {
        if (checkbox !== this) {
            checkbox.disabled = this.checked;
        }
    });
});
</script>
@endpush

@endsection
