<?php

namespace App\EventSubscriber;

use App\Entity\Notification;
use App\Event\EvaluationEvent;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class EvaluationSubscriber implements EventSubscriberInterface
{


    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly RouterInterface        $router,
        private readonly EtudiantRepository     $etudiantRepository
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            EvaluationEvent::EVALUATED => 'onEvaluationEvent',
        ];
    }

    public function onEvaluationEvent($event): void
    {
        $eval = $event->getEvaluation();

        $etudiantSearch = $eval->getTrace()->getBibliotheque()->getEtudiant();
        $etudiant = $this->etudiantRepository->find($etudiantSearch->getId());
        $trace = $eval->getTrace();

        $this->addNotification($etudiant, EvaluationEvent::EVALUATED, $trace);
    }

    private function addNotification($etudiant, string $codeEvent, $trace): void
    {
        $notif = new Notification();
        $notif->setEtudiant($etudiant);
        $notif->setTypeUser(Notification::ETUDIANT);
        $notif->setType($codeEvent);
        $notif->setLu(false);
        $notif->setUrl($this->router->generate(
            'app_trace_index', ['id' => $trace->getId()]
        ));
        $this->entityManager->persist($notif);

        $this->entityManager->flush();
    }
}
