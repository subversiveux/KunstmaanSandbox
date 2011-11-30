<?php
// src/Kunstmaan/DemoBundle/DataFixtures/ORM/SlideGalleryFixtures.php

namespace Kunstmaan\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Kunstmaan\MediaBundle\Entity\SlideGallery;
use Kunstmaan\MediaBundle\Entity\Slide;

class SlideGalleryFixtures extends AbstractFixture implements OrderedFixtureInterface
{
    public function load($manager)
    {
        $gal = new SlideGallery();
        $gal->setName('First slide Gallery');
        $manager->persist($gal);
        $manager->flush();

            $subgal = new SlideGallery();
            $subgal->setParent($gal);
            $subgal->setName('Sub of first slide Gallery');
            $manager->persist($subgal);
            $manager->flush();

                $subgal3 = new SlideGallery();
                $subgal3->setParent($subgal);
                $subgal3->setName('Sub of the first sub');
                $manager->persist($subgal3);
                $manager->flush();

            $subgal2 = new SlideGallery();
            $subgal2->setParent($gal);
            $subgal2->setName('Second sub of first slide Gallery');
            $manager->persist($subgal2);

        $manager->flush();

        $gal2 = new SlideGallery();
        $gal2->setName('Second slide Gallery');
        $manager->persist($gal2);
        $manager->flush();

        $slide = new Slide();
        $slide->setName('Optimizing for happiness');
        $slide->setContent('4ebaea7763912f032300cbe8');
        $slide->setType('speakerdeck');
        $slide->setGallery($gal);
        $manager->persist($slide);
        $manager->flush();

        $slide = new Slide();
        $slide->setName('Introduction to Speakerdeck');
        $slide->setContent('4d0bcd025753086fc2000002');
        $slide->setType('speakerdeck');
        $slide->setGallery($gal);
        $manager->persist($slide);
        $manager->flush();

        $slide = new Slide();
        $slide->setName('Why social media is shit');
        $slide->setContent('10285826');
        $slide->setType('slideshare');
        $slide->setGallery($gal);
        $manager->persist($slide);
        $manager->flush();

    }

    public function getOrder()
    {
        return 1;
    }

}