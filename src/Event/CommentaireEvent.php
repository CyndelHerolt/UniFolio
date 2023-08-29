<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class CommentaireEvent extends Event
{
    final public const COMMENTED = 'commentaire.publie';

    public function __construct(protected $commentaire)
    {
    }

    public function getCommentaire(): mixed
    {
        return $this->commentaire;
    }
}