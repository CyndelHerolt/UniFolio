<?php
/*
 * Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
 * @author cyndelherolt
 * @project UniFolio
 */
namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class CommentaireEvent extends Event
{
    final public const COMMENTED = 'commentaire.publie';
    final public const RESPONDED = 'commentaire.reponse';

    public function __construct(protected $commentaire)
    {
    }

    public function getCommentaire(): mixed
    {
        return $this->commentaire;
    }
}