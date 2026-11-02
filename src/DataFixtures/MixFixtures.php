<?php

namespace App\DataFixtures;

use App\Entity\Mix;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

// cf. cours 12, Fixtures
class MixFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (self::getMixesArray() as [$title, $artist, $duration]) {
            $mix = new Mix();
            $mix->setTitle($title)
                ->setArtist($artist)
                ->setDuration($duration);

            $manager->persist($mix);
        }

        $manager->flush();
    }

    public static function getMixesArray(): array
    {
        return [
            ['Jungle Roots Mixtape vol.3', 'DJ Amen Break', '62 min'],
            ['Steppa Session Live @ Guinguette', 'Selecta Bass Lion', '95 min'],
        ];
    }
}
