<?php

namespace StoreBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AlbumRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AlbumRepository extends EntityRepository
{
    /**
     * 统计相册
     * @return mixed
     */
    public function countAlbum()
    {
        return $this->_em->createQueryBuilder()
            ->select("COUNT(a.id)")
            ->from('StoreBundle:Album', 'a')
            ->getQuery()->getSingleScalarResult();
    }
}
