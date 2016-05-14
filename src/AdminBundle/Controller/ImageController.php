<?php

namespace AdminBundle\Controller;

use StoreBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Util\SecureRandom;

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
     * @Route("/dropZone", name="admin_imageDropZone")
     * @Template()
     *
     * @return array
     */
    public function dropZoneAction()
    {
        return [];
    }

    /**
     *
     * @Route("/list/{page}", name="admin_imageList", defaults={"page":1}, requirements={"page"="\d+"})
     * @Template()
     *
     * @param Request $request
     * @param $page
     * @return array
     */
    public function listAction( Request $request, $page )
    {
        $paginator = $this->get('knp_paginator');

        /** @var Image $imageEntity */
        $imageEntity = $this->getDoctrine()->getRepository('StoreBundle:Image');

        $images = $imageEntity->createQueryBuilder('i')
            ->where('i.postId IS NULL');

        $pagination = $paginator->paginate($images, $page, 15);
        $response = [
            'html' => $this->renderView('AdminBundle:Image:ajaxList.html.twig', compact('pagination'))
        ];

        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     *
     * @Route("/upload", name="admin_imageUpload")
     *
     * @param Request $request
     * @return array
     */
    public function uploadAction( Request $request )
    {
        $response = [];

        /** @var $file \Symfony\Component\HttpFoundation\File\UploadedFile */
        if( $file = $request->files->get('image') )
        {
            $dateTime = new \DateTime();
            $dir = 'uploads/images/'.$dateTime->format('Y/m');

            $generator = new SecureRandom();
            $random = $generator->nextBytes(10);
            $hashedRandom = md5($random); // see tip below
            $name = $hashedRandom.'.'.$file->guessExtension();
            $fs = new Filesystem();
            if( !$fs->exists( $dir ) )
            {
                try {
                    $fs->mkdir( $dir );
                } catch (IOException $e) {
                    echo "An error occurred while creating your directory at ".$e->getPath();
                }
            }
            $fileData = $file->move( $dir,  $name );

            $image = new Image();
            $image->setExtension( $fileData->getExtension() )
                ->setImageName( $fileData->getFilename() )
                ->setImagePath( $fileData->getPath() )
                ->setRealPath( $fileData->getRealPath() )
                ->setImageSize( $fileData->getSize() );

            $em = $this->getDoctrine()->getManager();

            $em->persist( $image );
            $em->flush();

            $response = [
                'imagePath'  => $image->getImagePath(),
                'imageName'  => $image->getImageName(),
                'imageUrl'   => $this->getParameter('source_url').'images/'.$image->getImageTime()->format('Y/m').'/'.$image->getImageName()
            ];
        }

        return new JsonResponse($response, Response::HTTP_OK);
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
