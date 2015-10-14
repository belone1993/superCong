<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/9/1
 * Time: 下午3:07
 */

namespace AdminBundle\Controller;


use StoreBundle\Entity\Album;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class GalleryController
 * @package AdminBundle\Controller
 *
 * @Route("/admin/gallery")
 */
class GalleryController extends Controller
{

    /**
     * 相册列表
     *
     * @Route("/album", name="admin_gallery_album", defaults={"page":1}, requirements={"page"="\d+"})
     * @Template()
     *
     * @param integer $page
     * @return array
     */
    public function albumAction( $page )
    {
        /** @var  $albumEntity Album */
        $albumEntity = $this->getDoctrine()->getRepository('StoreBundle:Album');

        $albums = $albumEntity->findAll();

        $albumTotal = $albumEntity->countAlbum();

        return [
            'albums'    => $albums,
            'pageTotal' => ceil( $albumTotal / 10 ),
            'page'      => $page,
            'postTotal' => $albumTotal,
            'startPost' => (($page - 1) * 10) + 1,
            'endPost'   => (($page - 1) * 10) + count( $albums )
        ];
    }

    /**
     *
     * @Route("/createAlbum", name="admin_gallery_createAlbum")
     *
     * @return JsonResponse
     */
    public function createAlbumAction()
    {
        $this->createFormBuilder();
        return [];
    }

    /**
     *
     * @Route("/create", name="admin_gallery")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction( Request $request )
    {
        $response = [
            'success'   => true,
            'errorCode' => '',
            'message'   => '操作成功',
            'data'      => ''
        ];

        $title = $request->get('albumTitle');

        $album = new Album();

        return new JsonResponse($response);
    }

    public function editAlbumAction()
    {
        return [];
    }
}