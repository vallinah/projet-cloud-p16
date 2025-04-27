<div class="body">
    <div class="menu" id="displayed_menu">
        <h2 class="header-menu">
            Navigation
        </h2>
        <!--/* liste menu a gauche ==================> METTRE UN ID UNIQUE SUR CHAQUE <u> ET LE METTRE DANS LA FONCTION disp_menu("l id ajouté") */-->
        <div class="index-menu" onclick="disp_menu('liste_1')">
            <span>Validation</span>
            <ul id="liste_1" style="display: none;">
                <li>
                    <a href="{{ route('ventes') }}">
                        <i class="bi bi-circle"></i><span>Vente</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('achats') }}">
                        <i class="bi bi-circle"></i><span>Achat</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('retraits') }}">
                        <i class="bi bi-circle"></i><span>Retrait</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('depots') }}">
                        <i class="bi bi-circle"></i><span>Depot</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="index-menu" onclick="disp_menu('liste_2')">
            <span>Historique</span>
            <ul id="liste_2" style="display: none;">
                <li>
                    <a href="{{ route('operations') }}">
                        <i class="bi bi-circle"></i><span>Operation</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>