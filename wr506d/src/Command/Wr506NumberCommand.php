<?php

namespace App\Command;

use App\Repository\ActorRepository;
use App\Repository\CategoryRepository;
use App\Repository\MovieRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'wr506:number',
    description: 'Retourne le nb de films, d\'acteurs, de catégories et le nb de films / catégories',
)]
class Wr506NumberCommand extends Command
{
    private ActorRepository $actorRepository;
    private CategoryRepository $categoryRepository;
    private MovieRepository $movieRepository;

    public function __construct(ActorRepository $actorRepository, CategoryRepository $categoryRepository, MovieRepository $movieRepository)
    {
        parent::__construct();
        $this->actorRepository = $actorRepository;
        $this->categoryRepository = $categoryRepository;
        $this->movieRepository = $movieRepository;
    }

    protected function configure(): void
    {
        $this
            ->setName('wr506:number')
            ->setDescription('Retourne le nb de films, d\'acteurs, de catégories et le nb de films / catégories')
            ->addArgument('type', InputArgument::REQUIRED, 'Type de données demandées');
        $this->addOption('log-file', null, InputOption::VALUE_OPTIONAL, 'Log to file', '/var/log/wr506.log');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $type = $input->getArgument('type');

        if ($type === 'actor') {
            $numberOfActors = $this->actorRepository->countActors();
            $io->success(sprintf("Nombre total d'acteurs : %d", $numberOfActors));
            return Command::SUCCESS;
        }

        if ($type === 'category') {
            $numberOfCat = $this->categoryRepository->countCat();
            $io->success(sprintf("Nombre total de catégories : %d", $numberOfCat));
            return Command::SUCCESS;
        }

        if ($type === 'movie') {
            $numberOfMovies = $this->movieRepository->countMovies();
            $io->success(sprintf("Nombre de films: %d", $numberOfMovies));
            return Command::SUCCESS;
        }

        if ($type === 'all') {
            $numberOfActors = $this->actorRepository->countActors();
            $numberOfCat = $this->categoryRepository->countCat();
            $numberOfMovies = $this->movieRepository->countMovies();
            $io->success(sprintf("Nombre total d'acteurs : %d\nNombre total de catégories : %d\nNombre de films: %d", $numberOfActors, $numberOfCat, $numberOfMovies));
            return Command::SUCCESS;
        }


        return Command::SUCCESS;
    }
}
