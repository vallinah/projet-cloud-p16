# Lancer et builder le projet:
Build:
->docker-compose up --build

Lancement:
->docker-compose up

# Accéder au conteneur PostgreSQL:
docker exec -it postgres_container psql -U cloud --dbname=projet_cloud_p16

# Mot de Passe de tous les 10 utilisateur sont tous:123456
