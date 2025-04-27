@extends('user.layout.layout-user')

@section('content')
<div class="contains">
    <h1 id="page">Commission</h1>
    <div class="dash">
        <div class="card" style="background-color: rgb(0, 255, 255);">
            <h2 class="card-title">Valeur Commission </h2>
            <p id="total-market-cap"><span>{{ number_format($commission->valeur, 0) }} %</span></p>
                
        </div>
    </div>
    <div class="form-container sm">
        <h2>Update commission</h2>
        <form action="{{ route('commission.update', $commission->id_commission) }}" method="POST">
            @csrf
            @method('PUT') 
            <div class="form-control">
                <label for="valeur" class="form-label">Nouvelle valeur de la commission</label>
                <input type="number" class="form-control" id="valeur" name="valeur" 
                                value="{{ $commission->valeur }}" required>
            </div>
            <div class="form-control">
                <label for="date_commission" class="form-label">Date de la commission</label>
                <input type="date" class="form-control" id="date_commission" name="date_commission" 
                        value="{{ \Carbon\Carbon::parse($commission->date_commission)->format('Y-m-d') }}" required>
            </div>

            <div class="button-div">
                <button type="submit" class="achat-btn">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>
@endsection
