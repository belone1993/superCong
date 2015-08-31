<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/8/31
 * Time: 上午11:00
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StoreBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PictureController
 * @package AppBundle\Controller
 *
 * @Route("/picture")
 */
class PictureController extends Controller
{
    /**
     *
     * @Route("/images")
     *
     * @param Request $request
     * @return Response
     */
    public function imagesAction( Request $request )
    {
        /** @var  $imageEntity Image */
        $imageEntity = $this->getDoctrine()->getRepository('StoreBundle:Image');

        $images = $imageEntity->findImagesByPage();
//        $this->render('AppBundle:Picture:images.html.twig', [
//            'images' => $images
//        ]);
        $content = $this->render('AppBundle:Picture:images.html.twig', [
            'images' => $images
        ]);

        return $content;
    }
}