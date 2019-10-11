<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ProjectContribution;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * LoadProjectContribution form.
 */
class LoadProjectContribution extends Fixture implements DependentFixtureInterface {
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $em) {
        for ($i = 0; $i < 4; $i++) {
            $fixture = new ProjectContribution();
            $fixture->setProject($this->getReference('project.1'));
            $fixture->setPerson($this->getReference('person.1'));
            $fixture->setOrganization($this->getReference('organization.1'));
            $fixture->setProjectRole($this->getReference('projectrole.1'));

            $em->persist($fixture);
            $this->setReference('projectcontribution.' . $i, $fixture);
        }

        $em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() {
        // add dependencies here, or remove this
        // function and "implements DependentFixtureInterface" above
        return array(
            LoadPerson::class,
            LoadProject::class,
            LoadOrganization::class,
            LoadProjectRole::class,
        );
    }
}
