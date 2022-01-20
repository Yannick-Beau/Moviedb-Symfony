<?php

namespace App\Command;

use App\Repository\MovieRepository;
use App\Service\OmdbApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    // Nom de la commande. Bonne pratique, on la préfixe avec "app:"
    name: 'app:movie:poster',
    description: 'Fetch movie posters from OMDB API',
)]
class MoviePosterCommand extends Command
{
    // Les services nécessaire à notre commande...
    private $movieRepository;
    private $entityManager;
    private $omdbApi;

    /**
     *  ... qu'on récupère en injection de dépendances ici
     */
    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $entityManager, OmdbApi $omdbApi)
    {
        $this->movieRepository = $movieRepository;
        $this->entityManager = $entityManager;
        $this->omdbApi = $omdbApi;

        // En PHP on doit appeler le constructeur de la classe parent
        // Si on en a dans l'enfant et que le parent également !
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // Argument = valeur à transmettre à la commande
            ->addArgument('title', InputArgument::OPTIONAL, 'Movie title to fetch')
            // "Option" qui change le comportement de la commande
            ->addOption('dump', 'd', InputOption::VALUE_NONE, 'Dump title movies')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        // On récupère l'argument "title" si présent
        $title = $input->getArgument('title');

        if ($title) {
            // Si un titre est présent, on ne traite que ce film
            $io->note(sprintf('Movie to fetch: %s', $title));
            $movie = $this->movieRepository->findOneBy(['title' => $title]);
            // On colle $movie dans un tableau $movies
            $movies = [$movie];
        } else {
            // Sinon on traite tous les films
            $io->note(sprintf('Fetching all movies'));
            $movies = $this->movieRepository->findAll();
        }

        // La logique métier / L'objectif de la commande

        // On récupère les films concernés
        // On boucle dessus, on va chercher les données associées sur OMDB API
        foreach ($movies as $movie) {
            if ($input->getOption('dump')) {
                $io->info('Fetching ' . $movie->getTitle());
            }
            // On envoie le titre du film à notre service OMDB API
            $moviePoster = $this->omdbApi->fetchPoster($movie->getTitle());
            if ($moviePoster === null) {
                $io->warning('Poster not found :scream:');
            }
            // On met à jour l'URL du poster dans le film
            $movie->setPoster($moviePoster);
        }
        // On flush
        $this->entityManager->flush();

        $io->success('All movies to fetched!');

        // Indique que la commande a fonctionné comme attendu
        return Command::SUCCESS;
    }
}
