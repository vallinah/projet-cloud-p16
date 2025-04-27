@extends('user.layout.layout-user')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Dépôt de fonds</div>
                <div class="card-body">
                    <form action="{{ route('wallet.deposit') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Montant à déposer</label>
                            <input type="number" step="0.01" name="montant" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Déposer</button>
                    </form>
                </div>
            </div>
        </div>