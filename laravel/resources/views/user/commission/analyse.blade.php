@extends('user.layout.layout-user')

@section('content')
<div class="contains">
    <h1 id="page">Analyse Commission</h1>
    <div class="form-container sm">
        <form action="{{ route('commission.validanalys') }}" method="POST">
            @csrf
            
            <div class="form-control">
                <label for="analysis_type">Type d'analyse</label>
                <select name="analysis_type" id="analysis_type" class="form-control">
                    @foreach($types as $type)
                        <option value="{{ $type->type_analyse }}">{{ $type->type_analyse }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-control">
                <label for="crypto">Cryptomonnaie</label>
                    <select name="crypto" id="crypto" class="form-control">
                        <option value="all">Tous</option>
                            @foreach($cryptocurrencies as $crypto)
                                <option value="{{ $crypto->crypto_id  }}">{{ $crypto->name }}</option>
                            @endforeach
                    </select>
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
                <button type="submit" class="achat-btn">Valider</button>
            </div>
        </form>
    </div>
</div>
@endsection
