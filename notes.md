## Bread :
- **Browse** => liste les enregistrement
- **Read** => lit un enregistrement
- **Edit** => met à jour un enregistrement
- **Add** => ajoute un enregistrement
- **Delete** => supprime un enregistrement

## Commandes

- ``` composer create-project symfony/website-skeleton nomDuProjet ``` => **création d'un projet à apartir du squelette symfony**
- ``` php bin/console make:migration ``` => **faire la migration des entités**
- ``` php bin/console doctrine:migrations:migrate ``` => **éxécuter la migration sur la BDD**
- ``` php bin/console make:controller nomDuController``` => **créer un controller**
- ``` php bin/console doctrine:schema:update --dump-sql ``` => **Vérifier la structure SQL**
- ``` php bin/console doctrine:schema:validate ``` => **voir si le mapping est corect avec la BDD**
- ``` php bin/console make:controller ``` => **Créer un controller**
- ``` php bin/console make:entity ``` => **Créer une entité ou modfier une enitté existente**

### DataFixtures
- ` php bin/console doctrine:fixtures:load ` => **Exécute les data fixtures pour remplir la BDD de données de test**

## Relations

### ManyToOne (1N)

- On identifie sur le MCD où va aller la clé étrangère (du côté du "1" sur la relation 1N).
- C'est donc cette entité qui va "détenir la relation". 
- Pour Doctrine, c'est la `ManyToOne` qui détient la relation, donc la `ManyToOne` est l'entité qui détient la clé étrangère.

## Autres

Le ? autorise le null.

```php 
/**
 * Méthode magique PHP
 * Sera appelée si on veut afficher un objet directement (ex: author)
 */
Public function __toString() {
    // Retourne le prénom et le nom
    return $this->firstname. ' ' . $this->lastname;
}

```

**fetch="EAGER"** => pour récupérer systématiquement l'objet de la relation

### Si une classe de Repository est manquante

On serait bloqués pour :
- "injecter" MovieRepository dans une méthode de contrôleur.
- Créer des requêtes custom.

Solution :
- On indique la classe de Repository au niveau du @ORM\Entity, par exemple `@ORM\Entity(repositoryClass=MovieRepository::class)` + un `use App\Repository\MovieRepository;`
- ou directement `@ORM\Entity(repositoryClass='App\Repository\MovieRepository')`
- Puis on exécute la commande `make:entity --regenerate`
- FQCN complet à saisir par exemple : `App\Entity\Movie`

### Faker librairie de fausse données

installation :
` composer require fakerphp/faker `