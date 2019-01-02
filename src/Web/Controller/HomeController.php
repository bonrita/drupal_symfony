<?php

namespace App\Web\Controller;


use App\Infrastructure\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route({ "en": "/", "nl": "/nl" },
     *     name="home_page",
     *     requirements={ "_locale" = "%app.locales%" })
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchType::class, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
        }

        return $this->render('pages/home/index.html.twig');
    }

}