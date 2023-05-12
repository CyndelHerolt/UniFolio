<?php
// src/Twig/AppExtension.php

namespace App\Twig;

use App\Classes\DataUserSession;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
//    private $dataUserSession;
//
//    public function __construct(DataUserSession $dataUserSession, RequestStack $session)
//    {
//        $this->dataUserSession = $dataUserSession;
//        $this->session = $session;
//    }
//
//    public function getFilters()
//    {
//        return [
//            new TwigFilter('filtre', [$this, 'filtreSemestres']),
//        ];
//    }
//
//    public function filtreSemestres($semestresActifs, $ordre)
//    {
//        $this->session->getSession();
//        $annee = null;
//        $annees = $this->dataUserSession->getAnnees();
//
//        foreach ($annees as $anneeItem) {
//            if ($anneeItem->getOrdre() === $ordre) {
//                $annee = $anneeItem;
//            }
//        }
//
//        if ($annee === null) {
//            return [];
//        }
//
//        return $this->dataUserSession->getSemestresByAnnee($annee, $ordre);
//    }
}
