<?php

namespace App\Twig\Components;

use App\Classes\DataUserSession;
use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('NotificationComponent')]
final class NotificationComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    /** @var Notification[] */
    public ?array $notifications = [];

    public function __construct(
        private NotificationRepository $notificationRepository,
        protected DataUserSession $dataUserSession,
    ) {

    }

    #[LiveAction]
    public function markAllAsRead(): void
    {
        $this->notifications = $this->dataUserSession->getNotifications();
        foreach ($this->notifications as $notification) {
            $notification->setLu(true);
            $this->notificationRepository->save($notification, true);
        }
    }

    #[LiveAction]
    public function deleteAll(): void
    {
        $this->notifications = $this->dataUserSession->getNotifications();
        foreach ($this->notifications as $notification) {
            if (!$notification->isLu()) {
                $this->notificationRepository->remove($notification, true);
            }
        }
    }

    public function getAllNotifications()
    {
        $this->notifications = $this->dataUserSession->getNotifications();

        return $this->notifications;
    }
}