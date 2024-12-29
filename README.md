# projet-cloud-p16
    git clone <URL_DU_DEPOT>
    cd <NOM_DU_DEPOT>

    Construire les Conteneurs

    Avant de démarrer les conteneurs, construisez-les avec la commande suivante :
    docker-compose up --build

    Cette commande :

        Télécharge les images nécessaires (PHP, ASP.NET,postgresql, etc.).

        Configure les applications Laravel et ASP.NET dans leurs environnements respectifs.

    Lancer les Conteneurs

    Une fois la construction terminée, lancez les conteneurs avec :
    docker-compose up

    Cette commande démarre les services définis dans le fichier docker-compose.yml.

    Accéder aux Applications

    Une fois les conteneurs démarrés, vous pouvez accéder aux applications via votre navigateur :

        Laravel : http://localhost:8000

        ASP.NET : http://localhost:5000

Pour arrêter les conteneurs en cours d'exécution, utilisez la commande suivante :
docker-compose down

Cette commande arrête et supprime les conteneurs tout en conservant les volumes de données (base de données, fichiers).


