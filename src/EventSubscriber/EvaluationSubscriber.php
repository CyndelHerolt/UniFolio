<?php

/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\EventSubscriber;

use App\Entity\Notification;
use App\Event\EvaluationEvent;
use App\Repository\EtudiantRepository;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class EvaluationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly RouterInterface $router,
        private readonly EtudiantRepository $etudiantRepository,
        private readonly NotificationRepository $notificationRepository
    ) {
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
        $origine = $eval;

        $etudiantSearch = $eval->getTrace()->getBibliotheque()->getEtudiant();
        $etudiant = $this->etudiantRepository->find($etudiantSearch->getId());
        $trace = $eval->getTrace();

        if ($eval->isEtat() !== 0) {
            $this->addNotification($etudiant, EvaluationEvent::EVALUATED, $trace, $origine);
        } else {
            $notification = $this->notificationRepository->findOneBy([
                'etudiant' => $etudiant,
                'type' => EvaluationEvent::EVALUATED,
                'validation' => $origine,
            ]);
            if ($notification) {
                $this->entityManager->remove($notification);
                $this->entityManager->flush();
            }
        }
    }

    private function addNotification($etudiant, string $codeEvent, $trace, $origine): void
    {
        $notif = new Notification();
        $notif->setEtudiant($etudiant);
        $notif->setTypeUser(Notification::ETUDIANT);
        $notif->setDateCreation(new \DateTime());
        $notif->setType($codeEvent);
        $notif->setLu(false);
        $notif->setValidation($origine);
        $notif->setUrl($this->router->generate(
            'app_trace_index',
            ['id' => $trace->getId()]
        ));
        $this->entityManager->persist($notif);

        $this->entityManager->flush();
    }
}
