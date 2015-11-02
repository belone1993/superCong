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
     *
     * @param Request $request
     * @return array
     */
    public function uploadAction( Request $request )
    {
        if( $request->files->get('image') )
        {
            $em = $this->getDoctrine()->getManager();

            $dateTime = new \DateTime();
            $dir = 'uploads/images/'.$dateTime->format('Y/m');

            /** @var $file \Symfony\Component\HttpFoundation\File\UploadedFile */
            foreach ($request->files->get('image') as $file)
            {
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

                $em->persist( $image );
                $em->flush();
            }
        }

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
