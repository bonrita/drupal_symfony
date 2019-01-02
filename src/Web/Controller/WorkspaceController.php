<?php

namespace App\Web\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WorkspaceController extends AbstractController {

  /**
   * @Route("/workspace/{name}", name="workspace_show")
   *
   * @param $name
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function showAction($name) {
    return $this->render(
      'pages/workspace/show.html.twig',
      ['project' => 'Symfony book']
    );
  }

}
