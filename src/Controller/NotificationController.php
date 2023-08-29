<?php

namespace App\Controller;

use App\Classes\DataUserSession;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

class NotificationController extends AbstractController
{
    public function __construct(
        NotificationRepository $notificationRepository,
        #[Required] public Security $security,
        DataUserSession $dataUserSession,
    )
    {
        $this->notificationRepository = $notificationRepository;
        $this->dataUserSession = $dataUserSession;
    }

    #[Route('/notification', name: 'app_notification')]
    public function index(): Response
    {
        $data_user = $this->dataUserSession;

        $etudiant = $this->security->getUser()->getEtudiant();
        $enseignant = $this->security->getUser()->getEnseignant();
        if ($etudiant) {
            $notifications = $this->notificationRepository->findBy(['etudiant' => $etudiant]);
        } elseif($enseignant) {
            $notifications = $this->notificationRepository->findBy(['enseignant' => $enseignant]);
        }



        return $this->render('notification/index.html.twig', [
            'notifications' => $notifications,
            'data_user' => $data_user,
        ]);
    }
}
