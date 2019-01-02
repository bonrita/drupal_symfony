<?php

namespace App\Web\Controller;

use App\Infrastructure\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SideBlocks
 *
 * This class houses all data snippets that are to be rendered in side blocks.
 *
 * @package App\Controller
 */
class SideBlocks extends AbstractController
{

    public function searchForm(): Response {
        $form = $this->createForm(SearchType::class, []);

        return $this->render('components/block-search-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}