<?php

namespace App\Controller;

use App\Classes\DataUserSession;
use App\Repository\NotificationRepository;
use App\Repository\PortfolioRepository;
use App\Repository\TraceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

class NotificationController extends AbstractController
{
    public function __construct(
        NotificationRepository      $notificationRepository,
        PortfolioRepository         $portfolioRepository,
        TraceRepository             $traceRepository,
        #[Required] public Security $security,
        DataUserSession             $dataUserSession,
    )
    {
        $this->notificationRepository = $notificationRepository;
        $this->portfolioRepository = $portfolioRepository;
        $this->traceRepository = $traceRepository;
        $this->dataUserSession = $dataUserSession;
    }

    #[Route('/notification', name: 'app_notification')]
    public function index(): Response
    {
        $data_user = $this->dataUserSession;

        $etudiant = $this->security->getUser()->getEtudiant();
        $enseignant = $this->security->getUser()->getEnseignant();
        if ($etudiant) {
            $notifications = $this->notificationRepository->findBy(['etudiant' => $etudiant], ['dateCreation' => 'DESC']);
        } elseif ($enseignant) {
            $notifications = $this->notificationRepository->findBy(['enseignant' => $enseignant], ['dateCreation' => 'DESC']);
        }

        foreach ($notifications as $notification) {
            $url = $notification->getUrl();
            parse_str(parse_url($url, PHP_URL_QUERY), $params);
            if (isset($params['id'])) {
                $id = $params['id'];
                if (str_contains($url, 'portfolio')) {
                    $notification->portfolio = $this->portfolioRepository->find($id);
                } elseif (str_contains($url, 'etudiant/bibliotheque/trace')) {
                    $notification->trace = $this->traceRepository->find($id);
                }
            }
        }


        return $this->render('notification/index.html.twig', [
            'notifications' => $notifications,
            'data_user' => $data_user,
            'trace' => $trace ?? null,
            'portfolio' => $portfolio ?? null,
        ]);
    }

    #[Route('/notification/delete/{id}', name: 'app_delete_notification')]
    public function delete($id): Response
    {
        $notification = $this->notificationRepository->find($id);
        $this->notificationRepository->remove($notification, true);

        return $this->redirectToRoute('app_notification');
    }

    #[Route('/notification/delete', name: 'app_delete_notification_all', methods: ['GET'])]
    public function deleteAll(): Response
    {
        $etudiant = $this->security->getUser()->getEtudiant();

        $notifications = $this->notificationRepository->findBy(['etudiant' => $etudiant]);
        foreach ($notifications as $notification) {
            $this->notificationRepository->remove($notification, true);
        }

        return $this->redirectToRoute('app_notification');
    }

    #[Route('/notification/lues', name: 'app_notification_lue_all')]
    public function markAllAsRead(): Response
    {
        $etudiant = $this->security->getUser()->getEtudiant();

        $notifications = $this->notificationRepository->findBy(['etudiant' => $etudiant]);
        foreach ($notifications as $notification) {
            $notification->setLu(true);
            $this->notificationRepository->save($notification, true);
        }

        return $this->redirectToRoute('app_notification');
    }

}