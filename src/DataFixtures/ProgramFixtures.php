<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Service\Slugify;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{

    private $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager): void
    {
        $program = new Program();
        $user = new User();
        $program->setTitle($this->slugify->generate($program->setTitle('Game of Thrones')));
        $program->setSlug($this->slugify->generate($program->getTitle()));
        $program->setSynopsis('La lutte pour le trone de fer');
        $program->setCategory($this->getReference('category_3'));
        $program->setCountry('USA');
        $program->setYear(2015);
        $program->setOwner($user->getEmail());
        for ($i=0; $i < count(ActorFixtures::ACTEURS); $i++) {
            $program->addActor($this->getReference('actor_' . $i));
        }
        $manager->persist($program);
        $manager->flush();

    }

    public function getDependencies()
    {
        return [
            ActorFixtures::class,
            CategoryFixtures::class,
        ];
    }


}
