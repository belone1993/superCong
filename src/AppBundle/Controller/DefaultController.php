<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class DefaultController
 *
 * @Route("/")
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/guestBook", name="guestBook")
     * @Template()
     */
    public function guestBookAction()
    {
        return [];
    }

    /**
     * @Route("/learn", name="learn")
     * @Template()
     */
    public function learnAction()
    {
        return [];
    }

    /**
     * @Route("/life", name="life")
     * @Template()
     */
    public function lifeAction()
    {
        return [];
    }

    /**
     * @Route("/about", name="about")
     * @Template()
     */
    public function aboutAction()
    {
        return [];
    }
}
