<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ImageController
 * @package AdminBundle\Controller
 *
 * @Route("/admin/image")
 */
class ImageController extends Controller
{
    /**
     *
     * @Route("/list/{page}", name="admin_imageList", defaults={"page":1}, requirements={"page"="\d+"})
     * @Template()
     *
     * @param $page
     * @return array
     */
    public function listAction( $page )
    {
        return [
            'page' => $page
        ];
    }

    /**
     *
     * @Route("/upload", name="admin_imageUpload")
     * @Template()
     *
     * @return array
     */
    public function uploadAction()
    {
        return [];
    }

    /**
     *
     * @Route("/create", name="admin_imageCreate")
     *
     * @return array
     */
    public function createAction()
    {
        return new JsonResponse(['success' => true, 'errorCode' => '000000']);
    }

}
