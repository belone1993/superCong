<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/10/29
 * Time: 上午11:52
 * File: PublishController.php
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PublishController
 *
 * @Route("/publish")
 *
 * @package AppBundle\Controller
 */
class PublishController extends Controller
{

    /**
     *
     * @Route("/putPost")
     *
     * @param Request $request
     * @return void
     */
    public function putPostAction( Request $request )
    {
        var_dump($request);die;
    }
}