<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use DateTimeImmutable;
use App\Entity\Actor;
use App\Entity\Movie;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Person($faker));

        $actors = $faker->actors($gender = null, $count = 190, $duplicates = false);
        $createdActors = [];
        foreach ($actors as $item) {
            $fullname = $item; //ex : Christian Bale
            $fullnameExploded = explode(' ', $fullname);

            $firstname = $fullnameExploded[0]; //ex : Christian
            $lastname = $fullnameExploded[1]; //ex : Bale

            $actor = new Actor();
            $actor->setLastname($lastname);
            $actor->setFirstname($firstname);
            $actor->setDob($faker->dateTimeThisCentury());
            $actor->setCreateAt(new DateTimeImmutable());
            $actor->setNationality($faker->countryCode());
            $actor->setGender($faker->randomElement(['Women', 'Men']));
            $actor->setDeathDate($faker->dateTimeBetween($actor->getDob(), 'now'));
            $actor->setBio($faker->text(2000));
            $actor->setAwards($faker->numberBetween(0, 100));

            $createdActors[] = $actor;

            $manager->persist($actor);
        }

        $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));
        $movies = $faker->movies(100);
        $createdMovies = [];
        foreach ($movies as $item) {
            $movie = new Movie();
            $movie->setTitle($item);
            $movie->setDirector($faker->name());
            $movie->setReleaseDate($faker->dateTimeThisCentury());
            $movie->setCreatedAt(new DateTimeImmutable());
            $movie->setEntries($faker->numberBetween(0, 10000));
            $movie->setDuration($faker->numberBetween(60, 180));
            $movie->setRating($faker->randomFloat(1, 0, 5));
            $movie->setMedia($faker->imageUrl(640, 480, $item));
            $movie->setOnline($faker->boolean());

            $createdMovies[] = $movie;

            shuffle($createdActors);
            $createdActorsSliced = array_slice($createdActors, 0, 4);
            foreach ($createdActorsSliced as $actor) {
                $movie->addActor($actor);
            }
            $manager->persist($movie);
        }

        $createdCategories = [];
        $categories = $faker->movieGenres();
        foreach ($movies as $item) {
            $categoryTitle = $faker->movieGenre();
            $category = new Category();
            $category->setTitle($categoryTitle);

            shuffle($createdMovies);
            $moviesSubset = array_slice($createdMovies, 0, 5);

            foreach ($moviesSubset as $movie) {
                $category->addMovie($movie);
            }

            $createdCategories[] = $categoryTitle;
            $manager->persist($category);
        }

        $manager->flush();
        return true;
    }
}
