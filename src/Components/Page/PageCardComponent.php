<?php

namespace App\Components\Page;

use App\Entity\Page;
use App\Repository\PageRepository;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('page_card')]
class PageCardComponent
{
    public int $id;

    public function __construct(private PageRepository $pageRepository)
    {}

    public function getPage(): Page
    {
        $page = $this->pageRepository->find($this->id);

        // Chargement des traces associées à la page
        $page->getTrace()->toArray();

        return $page;
    }
}
