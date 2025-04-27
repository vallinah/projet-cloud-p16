@extends('admin.layout.layout-admin')

@php
    use App\Models\User;
@endphp

@section('content')
<div class="contains">
    <div class="form-container sm">
        <h2>Validation des Transactions</h2>
        <form method="GET" action="{{ route('operations') }}">
            <div class="form-control">
                <label for="date_debut">Date Début:</label>
                <input type="date" id="date_debut" name="date_debut" value="{{ request('date_debut') }}">
            </div>
            <div class="form-control">
                <label for="date_fin">Date Fin:</label>
                <input type="date" id="date_fin" name="date_fin" value="{{ request('date_fin') }}">
            </div>
            <div class="form-control">
                <label for="user_id">Utilisateur:</label>
                <select id="user_id" name="user_id">
                    <option value="all">Tous</option>
                    @foreach($users as $user)
                        <option value="{{ $user->user_id }}" {{ request('user_id') == $user->user_id ? 'selected' : '' }}>
                            {{ $user->first_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-control">
                <label for="crypto_id">Cryptomonnaie:</label>
                <select id="crypto_id" name="crypto_id">
                    <option value="all">Toutes</option>
                    @foreach($cryptocurrencies as $crypto)
                        <option value="{{ $crypto->crypto_id }}" {{ request('crypto_id') == $crypto->crypto_id ? 'selected' : '' }}>
                            {{ $crypto->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="button-div">
                <button type="submit" class="vente-btn">Filtrer</button>
            </div>
        </form>
    </div>

    <div class="table-container lg">
        <table>
            <thead>
                <tr>
                    <th>RANG</th>
                    <th>UTILISATEUR</th>
                    <th>CRYPTO</th>
                    <th>COURS</th>
                    <th>NOMBRE</th>
                    <th>TYPE</th>
                    <th>DATE DE MOUVEMENT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($operations as $index => $operation)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="user-info">
                                @php
                                    $profileImage = User::find($operation->user_id)->getProfileImage();
                                @endphp
                                @if($profileImage)
                                    <img src="{{ $profileImage }}" 
                                         alt="Profile de {{ $operation->first_name }}"
                                         class="profile-image">
                                @else
                                    <div class="profile-placeholder">
                                        <span>{{ substr($operation->first_name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <a href="{{ route('operations.user', ['id' => $operation->user_id]) }}">
                                    {{ $operation->first_name }}
                                </a>
                            </div>
                        </td>
                        <td>{{ $operation->crypto_name }}</td>
                        <td>{{ number_format($operation->cours, 2, ',', ' ') }} USD</td>
                        <td>{{ $operation->nombre }}</td>
                        <td>{{ $operation->achat == 1 ? 'Achat' : 'Vente' }}</td>
                        <td>{{ \Carbon\Carbon::parse($operation->date_mouvement)->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
.user-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.profile-image {
    width: 2rem;
    height: 2rem;
    border-radius: 9999px;
    object-fit: cover;
}

.profile-placeholder {
    width: 2rem;
    height: 2rem;
    border-radius: 9999px;
    background-color: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
}

.profile-placeholder span {
    color: #4b5563;
    font-size: 0.875rem;
}

.user-info a {
    color: inherit;
    text-decoration: none;
}

.user-info a:hover {
    text-decoration: underline;
}
</style>
@endsection