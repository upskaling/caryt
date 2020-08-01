# un lecteur de flux pour les vidéos

![log caryt](p/logo_caryt.png)

## Avertissements

caryt n’est fourni avec aucune garantie.

## lecteur RSS pour

- Youtube
- PeerTube

## installation

- Récupérez l’application caryt via la commande git ou en téléchargeant l’archive
- Placez l’application sur votre serveur (la partie à exposer au Web est le répertoire ./p/)
- Le serveur Web doit avoir les droits d’écriture dans le répertoire ./data/ et ./p/

### crontab

```
0 */1 * * * php /path/to/caryt/app/actualize.php 2>&1
```

### nginx

configuration nginx dans `nginx.conf`

### utilisateur mot de passe par défaut

AUTH_USER: `admin`

AUTH_PW: `admin`

## ce projet utilise

- [Youtube-dl](https://github.com/ytdl-org/youtube-dl)
- [SimplePie](https://www.simplepie.org/)
- [bootstrap](https://github.com/twbs/bootstrap)
- php
- sqlite

## inspirer de

- [freshrss](https://github.com/FreshRSS/FreshRSS)
- [shaarli](https://github.com/shaarli/Shaarli)

## les choses à faire

- écrire des tests
- s'occuper du CSS
- ajouter de la recherche
- ajouter une option sans téléchargement
- ajouter la possibilité de mettre des podcast
- ajouter une page de install
  - télécharger YouTube-DL
  - configurer le logiciel
