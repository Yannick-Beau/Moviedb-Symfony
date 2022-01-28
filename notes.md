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
- `php bin/console make:controller --no-template` => **Créer un controller sans template**
- ``` php bin/console make:entity ``` => **Créer une entité ou modfier une enitté existente**
- ` php bin/console make:form ` => **Créer une classe form**
- ` php bin/console security:hash-password ` => **Hasher un password**
- ` php bin/console make:auth ` => **Permet de créer le formulaire d'authentification**
- `php bin/console make:user` => **Créer une entité  user**
-  `php bin/console debug:router --show-controlers` => **Permet de voir les routes ainsi que leur controller**
-  ` php bin/console make:crud ` => **Créer un controller avec toute les methode du BREAD et les template twig**
-  ` php bin/console make:command` => **Permet de créer une commande ex: app:movie:poster pour récupérer le poster des film depuis une api**
-  `php bin/console list app` => **Permet de lister la liste des commande de app**
-  `php bin/console debug:autowiring xxx` => **Permet de chercher un service avec un mot clé**
-   `php bin/console make:subscriber` => **Créer un subscriber/ écouteur d'événement**

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

### EasyAdmin 3

**Installation :**
`composer require easycorp/easyadmin-bundle`

# Doctrine

## Lecture

Via le *Repository* de l'entité.

Ecriture 1 : classique
```php
$movieRepository = $this->getDoctrine()->getRepository(Movie::class);
$movie = $movieRepository->find($id);
```

Ecriture 2 : Injection du Repository dans la méthode
```php
    /**
     * Affiche un article
     *
     * @Route("/post/read/{id}", name="post_read", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function read(PostRepository $postRepository, $id)
    {
        $post = $postRepository->find($id);
```

Ecriture 3 : Usage du ParamConverter
```php
    /**
     * Supprimer un article
     * 
     * ParamConverter => si $post = null, alors notre contrôleur est exécuté
     * 
     * @Route("/post/delete/{id<\d+>}", name="post_delete", methods={"GET"})
     */
    public function delete(Post $post, EntityManagerInterface $entityManager)
    {
```
## Ecriture(s)

Ajout, modification, suppression via le *Manager*.

## Active Record VS Data Mapper

```php
// AR
// L'objet "peut tout faire"
$movie->save();
$movie->findAll();
$movie->find(1);
// Update/Delete
$movie->find(1);
$movie->title = 'new title';
$movie->save();
// Ou
$movie->delete();
// DM
// L'objet est manipulé par
// Le manager
$manager->persist($movie);
$manager->flush();
// Le Repository
$movieRepository->findAll();
$movieRepository->find(1);
// Update
$movie = $movieRepository->find(1);
$movie->setTitle('new title');
$manager->flush();
// Delete
$movie = $movieRepository->find(1);
$manager->remove($movie);
$manager->flush();
```

# Test
Installation :
- `composer require --dev phpunit/phpunit symfony/test-pack`

Création du fichier .env.test.local avec la database de test puis on exécute les commande pour créer la DB:
- `php bin/console --env=test doctrine:database:create`
- ensuite on applique les migrations => `php bin/console --env=test doctrine:migrations:migrate`
-  puis on relance les datafeaxture => `php bin/console --env=test doctrine:fixtures:load`

Exécuter les test :
- `php bin/phpunit --testdox`
Créer un test :
- `php bin/console make:test`