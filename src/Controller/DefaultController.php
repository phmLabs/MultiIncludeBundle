<?php

namespace phmLabs\MultiIncludeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('phmLabsMultiIncludeBundle:Default:index.html.twig', array('name' => $name));
    }
}
