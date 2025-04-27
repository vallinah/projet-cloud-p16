<div class="body">
    <div class="menu" id="displayed_menu">
        <h2 class="header-menu">
            Navigation
        </h2>
        <div class="index-menu" onclick="disp_menu('liste_1')">
            <span>Cours</span>
            <ul id="liste_1" style="display: none;">
                <li>
                    <a href="{{ route('home') }}">
                        <i class="bi bi-circle"></i><span>Cours</span>
                    </a>
                </li>
            </ul>
        </div>
        <!--/* liste menu a gauche ==================> METTRE UN ID UNIQUE SUR CHAQUE <u> ET LE METTRE DANS LA FONCTION disp_menu("l id ajouté") */-->
        <div class="index-menu" onclick="disp_menu('liste_2')">
            <span>Account</span>
            <ul id="liste_2" style="display: none;">
                <li>
                    <a href="{{ route('portofilo') }}">
                        <i class="bi bi-circle"></i><span>Portefeuille</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('fond') }}">
                        <i class="bi bi-circle"></i><span>Fond</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('filtre') }}">
                        <i class="bi bi-circle"></i><span>Filtre</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Commission -->
        <div class="index-menu" onclick="disp_menu('liste_3')">
            <span>Commission</span>
            <ul id="liste_3" style="display: none;">
                <li>
                    <a href="{{ route('commission') }}">
                        <i class="bi bi-circle"></i><span>Update commission</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('commission.analyse') }}">
                        <i class="bi bi-circle"></i><span>Analyse commission</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- historique operation pour admin -->
        <div class="index-menu" onclick="disp_menu('liste_4')">
            <span>Vente et achat</span>
            <ul id="liste_4" style="display: none;">
                <li>
                    <a href="{{ route('userhistory') }}">
                        <i class="bi bi-circle"></i><span>Historiqe operations</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- evolution graphique -->
        <div class="index-menu" onclick="disp_menu('liste_5')">
            <span>Evolution graphique</span>
            <ul id="liste_5" style="display: none;">
                <li>
                    <a href="{{route('evolution') }}">
                        <i class="bi bi-circle"></i><span>Evolution graphique</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- analyse crypto -->
        <div class="index-menu" onclick="disp_menu('liste_6')">
            <span>Analyse crypto</span>
            <ul id="liste_6" style="display: none;">
                <li>
                    <a href="{{ route('analysis.form') }}">
                        <i class="bi bi-circle"></i><span>Analyse Crypto</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
