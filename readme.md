# Spécifications technique

## Serveur

- Version PHP 8.0.x
- Extension PHP : PDO
- MariaDB (version 10.4)

## Front

- HTML5 (Twig)
- CSS3
- Bootstrap
- Javascript

## Back

- Minimum PHP 8.0
- Symfony (5.3.9)
- mySql

<hr>

## Les choix techniques

### Sécurité

<p>
    Au niveau des mesures de sécurité j'ai décidé d'appliqué plusieurs pratiques recommandées.

    1.Hashage du mot de passe en base de donnée.

<i>Afin de ne pas compromettre le mot de passe de mon utilisateur j'utlise la fonction d'encodage de mot de passe livré avec l'authenticator de symfony, je peux définir moi même un algorithme de cryptage mais j'ai opté pour laissé à symfony d'appliquer de manière automatique le meilleur algorithme d'encodage (généralement bcrypt ou argon).
</i><br>

    2.Le csrf token

<i>
La protection CSRF fonctionne en ajoutant un champ masqué à mon formulaire qui contient un token Cela garantit que l'utilisateur <b> et non une autre "entité" </b> soumet les données fournies au formulaire.

</i>

    3.La double authentification

<i>
L’authentification à double facteur permet d’ajouter une couche de sécurité supplémentaire à notre système de connexion. En plus d’un mot de passe, L'utilisateur devra fournir un code de vérification généré  par notre application.
</i>
</p>

<hr>

## Comment utiliser le projet en local

```bash
git clone https://github.com/Papoel/ECF-Mediatheque
```

```bash
composer instal
```

Une fois le projet installé il faut créer la base de donnée :

```bash
symfony console doctrine:database:create
```

Jouer les migrations :

```bash
symfony console doctrine:migrations:migrate
```

Lancer les DataFixtures :

```bash
symfony console doctrine:fixture:load -n
```

Pour la gestion des mails en local vous pouvez utiliser votre mailer habituel, j'utilise personellement `mailhog`.
`localhost:8025`

> [INFO]
> La commande <i>symfony console</i> peut être remplacée dans votre terminal par <i>php bin/console</i> si vous n'utilisez pas le <i>cli symfony</i>

## Se connecter à l'application

|email   |mot de passe   |
|---|---|
| admin@email.fr  |  password |
| libraire@email.fr  |  password |
| user{x}@email.fr  |  password |

## Lien utiles

Dans cette section je met à disposition tous ce dont je me suis appuyé pour réaliser cette application :

- [Trello](https://trello.com/b/fFBVPI9c/ecf-mediatheque)
- [Projet Github](https://github.com/Papoel/ECF-Mediatheque)
