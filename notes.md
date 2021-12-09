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