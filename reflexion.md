# Securité

### Liste des problèmes

Mise en place d'une double authentifaication, okay mais à chaque connexion je dois resaisir le code qui est envoyé par mail à chaque connexion...
Pas terrible => Supprimer la double authentification ?

- Solutions
Après recherche et test voici la démarche à suivre pour corriger le problème :
Il faut mettre en place un sysyteme de confiace.

<div style="margin-left:30px">

```yaml
trusted_device:
    enabled: true
    lifetime: 5184000
    extend_lifetime: false
    cookie_name: mediatheque
    cookie_secure: false
    cookie_same_site: "lax"
    cookie_path: "/"
```
</div>

### Choix

Au départ j'ai créé une entity Libraire pour gérer les employés après réflexion cette entity est inutile j'ai décidé de renommé mon entity Adherent en User et et de supprimer l'entity Libraire, un libraire est un User avec un rôle spécifique, je passerai donc un attribut isGranted sur les routes réservés.