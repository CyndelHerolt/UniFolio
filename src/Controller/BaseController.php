<?php

namespace App\Controller;

use App\Classes\DataUserSession;
use App\Entity\Departement;
use App\Repository\DepartementRepository;
use App\Repository\EnseignantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Service\Attribute\Required;

class BaseController extends AbstractController
{

    protected DataUserSession $dataUserSession;
    protected DepartementRepository $departementRepository;
    protected EnseignantRepository $enseignantRepository;


    #[Required]
    public function setDataUserSession(DataUserSession $dataUserSession): void
    {
        $this->dataUserSession = $dataUserSession;

    }

    public function getDataUserSession(): DataUserSession
    {
        return $this->dataUserSession;
    }

    public function getDepartement(): ?Departement
    {
        return $this->dataUserSession->getDepartement();
    }

}