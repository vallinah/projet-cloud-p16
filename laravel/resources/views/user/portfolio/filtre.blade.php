@extends('user.layout.layout-user')

@section('content')
<div class="contains">
    <div class="form-container sm">
        <h2>Filtre user</h2>
        <form method="POST" action="{{ route('user.filtre')}}">
            @csrf
            <div class="form-control">
                <label for="start_datetime">Date et heure max</label>
                <input type="datetime-local" name="start_datetime" id="start_datetime" 
                                   class="form-control" value="{{ old('start_datetime') }}">
            </div>

            <div class="button-div">
                <button type="submit" class="achat-btn">Valider</button>
            </div>
        </form>
    </div>
</div>
@endsection
