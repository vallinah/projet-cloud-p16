# Lancer et builder le projet

## Build
```bash
docker-compose up --build
```

## Lancement
```bash
docker-compose up
```

# Accéder au conteneur PostgreSQL
```bash
docker exec -it postgres_container psql -U cloud --dbname=projet_cloud_p16
```

# Information utilisateur
Le mot de passe des 10 utilisateurs est : `123456`

