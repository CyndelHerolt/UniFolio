<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\EventSubscriber;

use App\Entity\Enseignant;
use App\Entity\Etudiant;
use App\Entity\Notification;
use App\Entity\Portfolio;
use App\Entity\Trace;
use App\Event\CommentaireEvent;
use App\Repository\CommentaireRepository;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class CommentaireSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly RouterInterface        $router,
        private readonly EtudiantRepository     $etudiantRepository,
        private readonly CommentaireRepository  $commentaireRepository,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CommentaireEvent::COMMENTED => 'onCommentaireEvent',
        ];
    }

    public function onCommentaireEvent($event): void
    {
        $commentaire = $event->getCommentaire();
        $origine = $commentaire;
        if ($commentaire->isVisibilite()) {
            if ($commentaire->getTrace() != null) {
                $etudiantSearch = $commentaire->getTrace()->getBibliotheque()->getEtudiant();
            } elseif ($commentaire->getPortfolio() != null) {
                $etudiantSearch = $commentaire->getPortfolio()->getEtudiant();
            }
            $user = $this->etudiantRepository->find($etudiantSearch->getId());
            if ($commentaire->getTrace() != null) {
                $destination = $commentaire->getTrace();
            } else {
                $destination = $commentaire->getPortfolio();
            }
            $this->addNotification($user, CommentaireEvent::COMMENTED, $destination, $origine);
        }

        if ($commentaire->getCommentaireParent()) {
            $commentaireParent = $this->commentaireRepository->find($commentaire->getCommentaireParent());
            $user = $commentaireParent->getEnseignant();

            if ($commentaire->getTrace() != null) {
                $destination = $commentaire->getTrace();
            } else {
                $destination = $commentaire->getPortfolio();
            }
            $this->addNotification($user, CommentaireEvent::RESPONDED, $destination, $origine);
        }
    }

    private function addNotification($user, string $codeEvent, $destination, $origine): void
    {
        if ($user instanceof Etudiant) {
            $notif = new Notification();
            $notif->setEtudiant($user);
            $notif->setDateCreation(new \DateTime());
            $notif->setTypeUser(Notification::ETUDIANT);
            $notif->setType($codeEvent);
            $notif->setLu(false);
            $notif->setCommentaire($origine);
            if ($destination instanceof Trace) {
                $notif->setUrl($this->router->generate(
                    'app_trace_index', ['id' => $destination->getId()]
                ));
            } elseif ($destination instanceof Portfolio) {
                $notif->setUrl($this->router->generate(
                    'app_portfolio_index', ['id' => $destination->getId(), 'step' => 'portfolio']
                ));
            }
        $this->entityManager->persist($notif);
        $this->entityManager->flush();
        } elseif ($user instanceof Enseignant) {
            $notif = new Notification();
            $notif->setEnseignant($user);
            $notif->setDateCreation(new \DateTime());
            $notif->setTypeUser(Notification::PERSONNEL);
            $notif->setType($codeEvent);
            $notif->setLu(false);
            $notif->setCommentaire($origine);
            if ($destination instanceof Trace) {
                $notif->setUrl($this->router->generate(
                    'app_bilan_eval_trace', ['id' => $destination->getId()]
                ));
            } elseif ($destination instanceof Portfolio) {
                $notif->setUrl($this->router->generate(
                    'app_portfolio_index', ['id' => $destination->getId(), 'step' => 'portfolio']
                ));
            }
            $this->entityManager->persist($notif);
            $this->entityManager->flush();
        }
    }
}
