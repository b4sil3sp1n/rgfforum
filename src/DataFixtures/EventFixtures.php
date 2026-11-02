<?php

namespace App\DataFixtures;

use App\Entity\Event;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

// cf. cours 12, Fixtures
class EventFixtures extends Fixture
{
    private $faker;

    public function __construct()
    {
        // On instancie Faker en langue française
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::getEventsArray() as [$title, $place, $description]) {
            $event = new Event();
            $event->setTitle($title)
                ->setPlace($place)
                ->setDescription($description)
                // Faker choisit une date dans les 6 prochains mois
                ->setDate(\DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('+1 week', '+6 months')));

            $manager->persist($event);
        }

        $manager->flush();
    }

    public static function getEventsArray(): array
    {
        return [
            ['Dub Guinguette #12', 'Guinguette du Port', "Roots & steppa toute la nuit au bord de l'eau, sound system 100% maison."],
            ['Jungle Is Massive', 'La Bobine', 'Session jungle / dnb roots, 100% vinyle, MC au micro.'],
        ];
    }
}
