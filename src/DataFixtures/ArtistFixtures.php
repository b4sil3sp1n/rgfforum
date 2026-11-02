<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

// cf. cours 12, Fixtures
class ArtistFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (self::getArtistsArray() as [$name, $role, $style]) {
            $artist = new Artist();
            $artist->setName($name)
                ->setRole($role)
                ->setStyle($style);

            $manager->persist($artist);
        }

        $manager->flush();
    }

    public static function getArtistsArray(): array
    {
        return [
            ['Selecta Bass Lion', 'Selecta', 'Roots / Dub'],
            ['MC Guinguette', 'MC', 'Toasting / Ragga'],
            ['DJ Amen Break', 'DJ', 'Jungle / DnB'],
        ];
    }
}
