<header>
    <div class="logo">
        <img onclick="Display('displayed_menu')" src="{{ asset('template/assets/icon/menu.png') }}" id="s_menu" alt="f">
        <img src="{{ asset('template/assets/icon/logo_icon.png') }}" alt="bitCoin">
        <h1>BitCoin</h1>
    </div>
    <div class="nav">
        <!-- Nom profil utilisateur et image -->
        <a class="user">
            <img src="{{ asset('template/assets/image/user/admin.jpg') }}" alt="user">
            <span>
               Administrateur
            </span>
        </a>

        <img onclick="Display('displayed')" id="menu" src="{{ asset('template/assets/icon/menu_bar.png') }}" alt="">
        <img onclick="Display('displayed')" id="menu2" src="{{ asset('template/assets/icon/menu_bar2.png') }}" alt="">
    </div>
    <!-- Liste top right -->
    <ul id="displayed">
        <li>
            <span>
                admin@gmail.com 
            </span>
        </li>

        <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}">
                <i class="bi bi-box-arrow-right"></i>
                <span>Déconnexion</span>
            </a>
        </li>
    </ul>
</header>
