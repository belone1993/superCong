<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/7/26
 * Time: 下午2:52
 */

namespace AdminBundle\Controller;
use StoreBundle\Entity\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class CategoryController
 * @package AdminBundle\Controller
 *
 * @Route("/admin/category")
 */
class CategoryController extends Controller
{

    /**
     * 分类列表
     * @Route("/list")
     * @Template()
     *
     * @return array
     */
    public function listAction()
    {
        /** @var  $category CategoryRepository */
        $category = $this->getDoctrine()->getRepository('StoreBundle:Category');

        $list = $category->findAll();

        $categoryTotal = $category->countCategoryAll();

        return [
            'categoryList'  => $list,
            'categoryTotal' => $categoryTotal
        ];
    }
}