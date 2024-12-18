#### README CONFIGURATION ASP.NET core EN UTILISANT UN CONTENEUR DOCKER

# Installation Images:

1. Télécharger l'image ASP.NET Core (Base et SDK)
   Les images utilisées dans votre Dockerfile proviennent du Microsoft .NET Docker Hub. Pour télécharger ces images, utilisez la commande docker pull :

Image de base pour l'exécution (ASP.NET Core) :

> docker pull mcr.microsoft.com/dotnet/aspnet:6.0

Image de SDK pour le build (Développement) :

> docker pull mcr.microsoft.com/dotnet/sdk:6.0

2. Vérification des images téléchargées:
   > docker images

# Configuration et Utilisation de l'Application ASP.NET Core avec Docker

Fichiers Clés:
Le Dockerfile contient les instructions nécessaires pour construire et exécuter l'image Docker de l'application ASP.NET Core:

# Étape 1 : Image de base pour l'exécution

FROM mcr.microsoft.com/dotnet/aspnet:6.0 AS base
WORKDIR /app
EXPOSE 80

# Étape 2 : Image de base pour le build

FROM mcr.microsoft.com/dotnet/sdk:6.0 AS build
WORKDIR /src
COPY ["AspNet.csproj", "./"]
RUN dotnet restore "./AspNet.csproj"
COPY . .
RUN dotnet build "AspNet.csproj" -c Release -o /app/build

# Étape 3 : Publication de l'application

FROM build AS publish
RUN dotnet publish "AspNet.csproj" -c Release -o /app/publish

# Étape 4 : Image finale pour l'exécution

FROM base AS final
WORKDIR /app
COPY --from=publish /app/publish .
ENTRYPOINT ["dotnet", "AspNet.dll"]

.dockerignore
Ce fichier exclut les fichiers inutiles lors de la construction de l'image Docker.bin/
obj/
_.user
_.db

# Instructions pour Construire et Exécuter l'Application:

## Étape 1 : Construire l'Image Docker

Dans le terminal, positionnez-vous dans le dossier du projet et exécutez la commande suivante :

> docker build -t aspnet .

## Étape 2 : Exécuter le Conteneur

Exécutez la commande suivante pour démarrer un conteneur basé sur l'image :

> docker run -d -p 5000:80 --name aspnet aspnet

### Gestion du Conteneur:

Arrêter le Conteneur:

> docker stop aspnet

Redémarrer le Conteneur:

> docker start aspnet

Accès à l'application:
Si l'application ne répond pas, vérifiez les journaux avec :

> docker logs aspnet

Acceder a l'url dans votre navigateur preferer:
http://localhost:5000/
