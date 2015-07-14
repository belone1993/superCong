<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/7/14
 * Time: 下午11:38
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class CategoryController
 * @Route("/category")
 * @package AppBundle\Controller
 */
class CategoryController extends Controller
{
    /**
     * @Route("/bar")
     * @Template()
     */
    public function barAction()
    {
        $category = $this->getDoctrine()->getRepository('AppBundle:Category');

        $categoryList = $category->findAll();

        return [
            'categoryList' => $categoryList
        ];
    }
}