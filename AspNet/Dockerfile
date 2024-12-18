# Étape 1 : Image de base pour le build
FROM mcr.microsoft.com/dotnet/aspnet:6.0 AS base
WORKDIR /app
EXPOSE 80
EXPOSE 443

# Étape 2 : Image de base pour le build
FROM mcr.microsoft.com/dotnet/sdk:6.0 AS build
WORKDIR /src
COPY ["AspNet.csproj", "./"]
RUN dotnet restore "./AspNet.csproj"
COPY . .
WORKDIR "/src/"
RUN dotnet build "AspNet.csproj" -c Release -o /app/build

# Étape 3 : Publier l’application
FROM build AS publish
RUN dotnet publish "AspNet.csproj" -c Release -o /app/publish

# Étape 4 : Image finale pour exécution
FROM base AS final
WORKDIR /app
COPY --from=publish /app/publish .
ENTRYPOINT ["dotnet", "AspNet.dll"]
