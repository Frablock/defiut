# Defiut

> Conçu par Camille LE BRECH, François PATINEC, Gabin LEGRAND, Gabriel ZENSEN DA SILVA

Application de défis type "CTF"

## Instalation

> Assurez vous d'avoir docker-compose installé\
> Clonnez ce repository

```sh
git clone https://github.com/frablock/defiut
```

Puis lancez la commande suivante : 

```
make start
```

Félicitations, vous venez de démarer l'application, il ne vous reste plus qu'à installer les certificats pour pouvoir l'utiliser !

### Installation des certificats SSL (non obligatoire pour utiliser l'API en HTTP)
Cette partie est obligatoire si vous voulez utiliser le front en même temps que l'API.\ <br/>
Lancez la commande adapté à votre système, en ayant lancé le `make start` avant

#### Sur Windows

```batch
docker compose cp php:/data/caddy/pki/authorities/local/root.crt %TEMP%/root.crt && certutil -addstore -f "ROOT" %TEMP%/root.crt
```

#### Sur Linux

##### Sur Ubuntu/Debian

```bash
docker cp $(docker compose ps -q php):/data/caddy/pki/authorities/local/root.crt /usr/local/share/ca-certificates/root.crt && sudo update-ca-certificates
```

##### Sur ArchLinux/CentOS

```bash
docker cp $(docker compose ps -q php):/data/caddy/pki/authorities/local/root.crt /etc/ca-certificates/trust-source/anchors/root.crt && sudo update-ca-certificates

sudo chmod 644 /etc/ca-certificates/trust-source/anchors/root.crt
sudo chown root:root /etc/ca-certificates/trust-source/anchors/root.crt
```

Une fois la/les commandes lancées, il ne vous reste plus qu'à redémarrer votre navigateur pour pouvoir utiliser l'application

### Données SQL

Lorsque vous aurez lancé le docker, vous n'aurez aucune données, pour remédier à cela, allez sur [http://localhost:8080](http://localhost:8080), connectez-vous (les mots de passes SQL sont inclus dans le fichier .env; pensez à les modifier)

Les deux fichiers suivants sont dans le répertoire `/utils/`

Importez ensuite defiut.sql pour créer les tables

Si vous voulez charger nos données d'exemples, importez le script dummy_data.sql

## Accès à l'application

Pour accéder à l'application, il vous suffit d'utiliser un navigateur et de vous rendre sur :
[http://localhost:80](http://localhost:80) pour accéder à l'application
[http://localhost:8080](http://localhost:8080) pour accéder à PHPMySQL
