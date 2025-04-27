@extends('user.layout.layout-user')

@section('content')
<div class="contains">
    <h1 id="page">Evolution crypto</h1>
    <div class="form-container sm">
        <form method="POST" action="{{ route('evolution-check')}}">
            @csrf
            <div class="form-control">
                <label for="crypto">Cryptomonnaie</label>
                <select name="crypto_id" id="crypto_id" class="form-control">
                    @foreach($cryptocurrencies as $crypto)
                        <option value="{{ $crypto->crypto_id }}">
                            {{ $crypto->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="button-div">
                <button type="submit" class="achat-btn">Valider</button>
            </div>
        </form>
    </div>
</div>
@endsection