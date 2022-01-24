<?php

namespace App\EventSubscriber;

use App\Repository\MovieRepository;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment as Twig;

class RandomMovieSubscriber implements EventSubscriberInterface
{
    /**
     * On appelle le service MovieRepository
     */
    private $movieRepository;

    /**
     * Twig
     */
    private $twig;

    public function __construct(MovieRepository $movieRepository, Twig $twig)
    {
        $this->movieRepository = $movieRepository;
        $this->twig = $twig;
    }
    
    public function onKernelController(ControllerEvent $event)
    {
        // Si requête API, on sort (pas de random movie si pas de HTML)
        // $request->getPathInfo() contient la route
        if (preg_match('/^\/api/', $event->getRequest()->getPathInfo())) {
            return;
        }
        // Récupérer le contrôleur
        $controller = $event->getController();
         // Avec les exceptions, le contrôleur n'est pas sous forme de tableau
        if (is_array($controller)) {
            // Récupérons le contrôleur, qui se trouve à l'index 0 du tableau
            // qui contient le contrôleur et la méthode à appeler (cf le dump)
            $controller = $controller[0];
        };
         // On récupère le nom de la classe de contrôleur
        $controllerClassName = (get_class($controller));
         // Notre écouteur ne s'éxécute pas partout
        // Uniquement depuis nos contrôleurs
        // soit qui commencent par 'App\Controller'
        if (strpos($controllerClassName, 'App\Controller') === false) {
            // On sort du suscriber
            return;
        }
        // 2. On va chercher un film au hasard
        // @todo Utiliser ORDER BY RAND() LIMIT 1
        // dans une requête custom dans le Respository

        // En attendant, on va faire un shuffle() sur tous les films
        $movies = $this->movieRepository->findAll();
        // On mélange, on prend le premier
        shuffle($movies);
        $randomMovie = $movies[0];
        // dump($randomMovie);

        // 3. On le transmet à Twig
        $this->twig->addGlobal('randomMovie', $randomMovie);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
