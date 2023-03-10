<?php

namespace App\DataFixtures;

use App\Entity\Page;
use App\Repository\TraceRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PageFixture extends Fixture implements OrderedFixtureInterface
{
    private $traceRepository;

    public function __construct(TraceRepository $traceRepository)
    {
        $this->traceRepository = $traceRepository;
    }
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $trace1 = $this->traceRepository->findOneBy(['titre' => 'trace n°1']);
        $trace2 = $this->traceRepository->findOneBy(['titre' => 'trace n°2']);
        $trace3 = $this->traceRepository->findOneBy(['titre' => 'trace n°3']);
        $trace4 = $this->traceRepository->findOneBy(['titre' => 'trace n°4']);

        $page1 = new Page();

        $page1->setIntitule('Page 1')
            ->setOrdre(1)
            ->addTrace($trace1)
            ->addTrace($trace2);
        $manager->persist($page1);

        $page2 = new Page();

        $page2->setIntitule('Page 2')
            ->setOrdre(2)
            ->addTrace($trace3)
            ->addTrace($trace4);
        $manager->persist($page2);

        $page3 = new Page();

        $page3->setIntitule('Page 3')
            ->setOrdre(2)
            ->addTrace($trace2)
            ->addTrace($trace4);
        $manager->persist($page3);

        $page4 = new Page();

        $page4->setIntitule('Page 4')
            ->setOrdre(2)
            ->addTrace($trace3)
            ->addTrace($trace1);
        $manager->persist($page4);

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getOrder()
    {
        return 4;
    }
}
