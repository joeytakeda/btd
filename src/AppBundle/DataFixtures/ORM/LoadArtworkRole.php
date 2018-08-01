<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ArtworkRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadArtworkRole form.
 */
class LoadArtworkRole extends Fixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        for($i = 0; $i < 4; $i++) {
            $fixture = new ArtworkRole();

            $em->persist($fixture);
            $this->setReference('artworkrole.' . $i);
        }

        $em->flush();

    }

}
