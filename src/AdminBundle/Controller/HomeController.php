<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/7/26
 * Time: 上午12:54
 */

namespace AdminBundle\Controller;

use StoreBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 * @package AdminBundle\Controller
 *
 * @Route("/admin/home")
 */
class HomeController extends Controller
{
    /**
     * @return string
     *
     * @Route("/index")
     *
     */
    public function indexAction()
    {
        return new Response( $this->renderView('AdminBundle:Default:dashboard.html.twig'));
    }
}