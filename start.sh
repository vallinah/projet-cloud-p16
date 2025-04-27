#!/bin/bash

# Exécuter la commande artisan pour mettre à jour les prix en arrière-plan
echo "Exécution de php artisan crypto:update-prices"
php /var/www/html/artisan crypto:update-prices &  # Lancer en arrière-plan

# Démarrer Apache en avant-plan
echo "Démarrage d'Apache"
apache2-foreground  # Démarrer Apache
    