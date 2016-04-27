<?php
/**
 * Created by PhpStorm.
 * User: LatteCake
 * Date: 15/10/29
 * Time: 上午11:52
 * File: PublishController.php
 */

namespace AppBundle\Controller;


use GuzzleHttp\Client;
use StoreBundle\Entity\Category;
use StoreBundle\Entity\Image;
use StoreBundle\Entity\Post;
use StoreBundle\Entity\User;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @var Logger
     */
    private $logger;

    /**
     *  @Route("/putImages")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function putImagesAction(Request $request)
    {
        $response = [];

        /** @var $file \Symfony\Component\HttpFoundation\File\UploadedFile */
        if( $file = $request->files->get('MarkdownImage') )
        {
            $md5File = md5($file);

            $logger = $this->get('logger');
            $logger->info("controller listeners. file------", [$md5File]);

            $dateTime = new \DateTime();
            $dir = 'uploads/images/'.$dateTime->format('Y/m');

            $random = random_bytes(10);
            $hashedRandom = md5($random); // see tip below
            $name = $hashedRandom.'.'.$file->guessExtension();
            $fs = new Filesystem();
            if( !$fs->exists( $dir ) ) {
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
                ->setMd5($md5File)
                ->setImageSize( $fileData->getSize() );

            $em = $this->getDoctrine()->getManager();

            $em->persist( $image );
//            $em->flush();

            $response = [
                'code' => 'success',
                'data' => [
                    'width'     => '',
                    'height'    => '',
                    'filename'  => $image->getImageName(),
                    'storename' => $file->getClientOriginalName(),
                    'size'      => $fileData->getSize(),
                    'path'      => $fileData->getPath(),
                    'hash'      => $md5File,
                    'timestamp' => $dateTime->getTimestamp(),
                    'url'       => $this->getParameter('source_url') . 'image/' . $dateTime->format('Y/m/') . $image->getImageName(),
                    'delete'    => $this->generateUrl('publish_delete_image', ['name' => $file->getFilename()])
                ],
            ];
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     *
     * @Route("/delImage", name="publish_delete_image")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteImageAction(Request $request)
    {
        return new JsonResponse($request->get('name'));
    }

    /**
     *
     * @Route("/putPost")
     *
     * @param Request $request
     * @return Response
     */
    public function putPostAction(Request $request)
    {
        $logger = $this->get('logger');
        $this->logger = $logger;

        $logger->info("controller. ", [$request]);

        $xmlString = file_get_contents('php://input');

        libxml_disable_entity_loader(true);
        $xmlObject = simplexml_load_string($xmlString);

        $response = '';

        if ($xmlObject) {
            $method = (string)$xmlObject->methodName;

            $logger->info("listener xml " . __METHOD__ . "\". ", [$method]);

            $username = (string)$xmlObject->params->param[1]->value->string;
            $password = (string)$xmlObject->params->param[2]->value->string;

            $logger->info("listener xml " . __METHOD__ . "\". ", [
                'username' => $username,
                'password' => $password
            ]);

            /** TODO 验证用户 */

            switch ($method) {
                case 'blogger.getUsersBlogs':
                    $response = $this->getUserBlog();
                    break;
                case 'wp.getCategories':
                case 'metaWeblog.getCategories':
                    $response = $this->getCreates();
                    break;
                case 'metaWeblog.newMediaObject':
                case 'metaWeblog.newPost':
                    $response = $this->createPost(0, $xmlObject->params->param[3]->value->struct->asXML(),
                        (boolean)$xmlObject->params->param[4]->value->boolean->asXML());
                    break;

            }
        }
        $logger->info("listener response " . __METHOD__ . "\". ", [$response]);
//        return new Response($response, 500);
        return new Response($response);
    }

    /**
     * 创建文章
     *
     * @param $id
     * @param $xmlString
     * @param bool $isPublish
     * @return string
     */
    public function createPost($id, $xmlString, $isPublish = false)
    {
        $this->logger->info("listener params .", [
            'id' => $id,
            'xmlString' => $xmlString,
            'isPublish' => $isPublish
        ]);
        /** @var \SimpleXMLElement $xmlObj */
        $xmlObj = simplexml_load_string($xmlString);

        if ($xmlObj) {
            $post = [];

            /** @var \SimpleXMLElement $child */
            foreach ($xmlObj->children() as $child) {

//                $_child = simplexml_load_string($child->asXML());
//
//                $name = $_child->getName();

                $name = (string)$child->name;

                if ('categories' == $name) {
                    $filename = $child->value->children()->asXML();
                } else {
                    $filename = $child->value->children();
                }
                $this->logger->info('listener xml params. ', ['name' => $name, 'filename' => $filename]);
                $filename = str_replace(['<array>', '</array>', '<data>', '</data>', '<string>', '</string>'], [], $filename);

                $post[$name] = $filename;
            }

            $this->logger->info('listener xml post', $post);

            $postObj = new Post();

            $userEntity = $this->getDoctrine()->getRepository('StoreBundle:User');

            $categoryEntity = $this->getDoctrine()->getRepository('StoreBundle:Category');

            /** @var User $author */
            $author = $userEntity->find(1);

            /** @var Category $category */
            $category = $categoryEntity->find(4);


            $this->logger->info('listener doctrine data. ', ['authId' => $author->getId(), 'categoryId' => $category->getId()]);

            $dateTime = new \DateTime();

            $postObj->setTitle($post['title'])
                ->setAuthor($author)
                ->setAction(1)
                ->setCategory($category)
                ->setDescription($post['wp_slug'])
                ->setContent($post['description'])
                ->setAuthorId(1)
                ->setCategoryId(0)
                ->setOldId(0)
                ->setStatus(1)
                ->setModified($dateTime)
                ->setIsMarkdown(1);

            $em = $this->getDoctrine()->getManager();

            $em->persist($postObj);
            $em->flush();

        }


        $strXML = '<methodResponse><params><param><value><boolean>$%#1#%$</boolean></value></param></params></methodResponse>';
        $strXML = str_replace("$%#1#%$", 1, $strXML);
        return $strXML;
    }

    /**
     * @return mixed|string
     */
    private function getUserBlog()
    {
        $strXML = '<?xml version="1.0" encoding="UTF-8"?><methodResponse><params><param><value><array><data><value><struct><member><name>url</name><value><string>$%#1#%$</string></value></member><member><name>blogid</name><value><string>$%#2#%$</string></value></member><member><name>blogName</name><value><string>$%#3#%$</string></value></member></struct></value></data></array></value></param></params></methodResponse>';

        $strXML = str_replace("$%#1#%$", $this->generateUrl('homepage'), $strXML);
        $strXML = str_replace("$%#2#%$", 1, $strXML);
        $strXML = str_replace("$%#3#%$", 'superCong', $strXML);

        return $strXML;
    }

    /**
     * @return mixed|string
     */
    private function getCreates()
    {
        $strXML = '<methodResponse><params><param><value><array><data>$%#1#%$</data></array></value></param></params></methodResponse>';
        $strSingle = '<value><struct>
<member><name>categoryId</name><value><string>$%#1#%$</string></value></member>
<member><name>parentId</name><value><string>$%#2#%$</string></value></member>
<member><name>categoryName</name><value><string>$%#3#%$</string></value></member>
<member><name>description</name><value><string>$%#4#%$</string></value></member>
<member><name>httpUrl</name><value><string>$%#5#%$</string></value></member>
<member><name>title</name><value><string>$%#6#%$</string></value></member>
</struct></value>';

        $strAll = '';

        $categoryEntity = $this->getDoctrine()->getRepository('StoreBundle:Category');

        $categories = $categoryEntity->findAll();

        /** @var Category $category */
        foreach ($categories as $category) {
            $s = $strSingle;
            $s = str_replace("$%#1#%$", $category->getId(), $s);
            $s = str_replace("$%#2#%$", 0, $s);
            $s = str_replace("$%#3#%$", $category->getCategoryName(), $s);
            $s = str_replace("$%#4#%$", '', $s);
            $s = str_replace("$%#5#%$", '', $s);
            $s = str_replace("$%#6#%$", $category->getCategoryName(), $s);

            $strAll .= $s;
        }

        $strXML = str_replace("$%#1#%$", $strAll, $strXML);
        return $strXML;
    }

    /**
     * @Route("/pushBaiDu")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public
    function puBaiDuAction(Request $request)
    {
        $postEntity = $this->getDoctrine()->getRepository('StoreBundle:Post');

        $posts = $postEntity->findAll();

        $urls = [];

        /** @var  $post Post */
        foreach ($posts as $post) {
            //$urls[] = $this->generateUrl('post_detail', ['id' => $post->getId()]);
            $urls[] = 'http://lattecake.com/post/' . $post->getId();
        }

        $api = 'http://data.zz.baidu.com/urls?site=www.lattecake.com&token=nRKurEJqZZFDGQwe';

//        $client = new Client();
//
//        $client->post($api, );
//
//        $urls = array(
//            'http://www.example.com/1.html',
//            'http://www.example.com/2.html',
//        );

        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $api,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", $urls),
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);

        return new Response($result, Response::HTTP_OK);
    }

    public function imagesAction(Request $request)
    {
        return $this->container;
    }
}