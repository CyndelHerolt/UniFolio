<?php

namespace App\EventSubscriber;

use App\Entity\Notification;
use App\Entity\Portfolio;
use App\Entity\Trace;
use App\Event\CommentaireEvent;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class CommentaireSubscriber implements EventSubscriberInterface
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
            $etudiant = $this->etudiantRepository->find($etudiantSearch->getId());
            if ($commentaire->getTrace() != null) {
                $destination = $commentaire->getTrace();
            } else {
                $destination = $commentaire->getPortfolio();
            }
            $this->addNotification($etudiant, CommentaireEvent::COMMENTED, $destination, $origine);
        }
    }

    private function addNotification($etudiant, string $codeEvent, $destination, $origine): void
    {
        $notif = new Notification();
        $notif->setEtudiant($etudiant);
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
    }
}
