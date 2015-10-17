<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/10/17
 * Time: 下午5:22
 * File: GuestBookController.php
 */

namespace AdminBundle\Controller;


use StoreBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class GuestBookController
 *
 * @Route("/admin/guestBook")
 *
 * @package AdminBundle\Controller
 */
class GuestBookController extends Controller
{
    /**
     * 留言列表
     * @Route("/list/{page}", defaults={"page":1}, requirements={"page"="\d+"})
     * @Template()
     *
     * @param Request $request
     * @return array
     */
    public function listAction( Request $request, $page )
    {
        /** @var  $commentEntity Comment */
        $commentEntity = $this->getDoctrine()->getRepository('StoreBundle:Comment');

        $comments = $commentEntity->findCommentByGuestBook( $page );

        return [
            'comments' => $comments
        ];
    }
}