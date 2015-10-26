<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/10/17
 * Time: 下午5:22
 * File: GuestBookController.php
 */

namespace AdminBundle\Controller;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Knp\Component\Pager\Paginator;
use StoreBundle\Entity\Comment;
use StoreBundle\Entity\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/list/{page}", name="admin_guestBook_list", defaults={"page":1}, requirements={"page"="\d+"})
     * @Template()
     *
     * @param Request $request
     * @return array
     */
    public function listAction( Request $request, $page )
    {

        /** @var  $commentEntity Comment */
        $commentEntity = $this->getDoctrine()->getRepository('StoreBundle:Comment');

//        $comments = $commentEntity->findCommentByGuestBook( $page, CommentRepository::COMMENT_LIMIT );
        /** @var $comments Query */
        $comments = $commentEntity->findGuestBookOrderBy();

        /** @var  $em EntityManager */
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery($comments->getDQL());

        /** @var  $paginator Paginator */
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $page /*page number*/,
            CommentRepository::COMMENT_LIMIT/*limit per page*/
        );

        return [
            'pagination' => $pagination,
        ];
    }

}