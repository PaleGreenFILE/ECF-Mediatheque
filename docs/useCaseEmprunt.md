b# Réserver un Livre

Dans la médiathèque un livre peut-être présent en plusieurs exemplaires le problème suivant se pose :

**Comment savoir quel livre est prêté à un utilisateur ?**

Etant donné que chaque livre à un ISBN unique et un nombre d'exemplaire j'ai décidé d'attribuer un n°de prêt qui comprendra l'ISBN et stock disponible de cette façon je suis en mesure de savoir quel livre est sortie.

## Exemple

Mr X emprunte le livre L'Étranger d'Albert Camus, Le n°d'emprunt sera ISBN-Stock => 212-3

<h3>Etat de la base de donnée</h3>

|  Isbn | Titre      |Auteur        | Exemplaire  |
|-------|------------|--------------|-------------|
|  212  | L'Étranger | Albert Camus | 3           |

<h2> 1. Emprunt de Mr X</h2>

|  Isbn | Titre      |Auteur        | N°Prêt  | Date_Emprunt | Retour Livre|
|-------|------------|--------------|---------|--------------|-------------|
|  212  | L'Étranger | Albert Camus | 212-3   | 05-10-2021   | 26-10-2021  |

<h5>MAJ - Livre dans la bdd</h5>

|  Isbn | Titre      |Auteur        | Exemplaire  |
|-------|------------|--------------|-------------|
|  212  | L'Étranger | Albert Camus | 2           |

<h2> 2. Emprunt de Mme Y</h2>

|  Isbn | Titre      |Auteur        | N°Prêt  | Date_Emprunt | Retour Livre|
|-------|------------|--------------|---------|--------------|-------------|
|  212  | L'Étranger | Albert Camus | 212-2   | 15-10-2021   | 05-11-2021  |

<h5>MAJ - Livre dans la bdd</h5>

|  Isbn | Titre      |Auteur        | Exemplaire  |
|-------|------------|--------------|-------------|
|  212  | L'Étranger | Albert Camus | 1           |

## Code

```php
    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $exemplaireDePret;
    ---
    public function getExemplaireDePret(): ?string
    {
        // return $this->exemplaireDePret;
        return $this->getIsbn().'-'. $this->getQuantite();
    }

    public function setExemplaireDePret(?string $exemplaireDePret): self
    {
        $this->exemplaireDePret = $exemplaireDePret;

        return $this;
    }
```
