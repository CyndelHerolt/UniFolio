<?php

namespace App\Controller;

use App\Classes\DataUserSession;
use App\Entity\Departement;
use App\Repository\EnseignantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Component\Security\Core\User\UserInterface;

class BaseController extends AbstractController
{

    protected DataUserSession $dataUserSession;


    #[Required]
    public function setDataUserSession(DataUserSession $dataUserSession): void
    {
        $this->dataUserSession = $dataUserSession;
    }

    public function getDepartement(): ?Departement
    {
        return $this->dataUserSession->getDepartement();
    }

}