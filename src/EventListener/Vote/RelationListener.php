<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace MSBios\Voting\Resource\Doctrine\EventListener\Vote;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\QueryBuilder;
use MSBios\Voting\Resource\Doctrine\Entity;

/**
 * Class RelationListener
 * @package MSBios\Voting\Resource\Doctrine\EventListener\Vote
 */
class RelationListener
{
    /**
     * @param Entity\Vote\Relation $entity
     * @param LifecycleEventArgs $args
     * @ORM\PostUpdate
     */
    public function onPostUpdate(Entity\Vote\Relation $entity, LifecycleEventArgs $args)
    {
        /** @var ObjectManager $dem */
        $dem = $args->getObjectManager();

        $result = $dem->createQueryBuilder('vr')
            ->select('SUM(vr.total) as result')
            ->andWhere('vr.poll = :poll')
            ->setParameter('poll', $entity->getPoll())
            ->getQuery()
            // ->getOneOrNullResult();
            ->getSingleScalarResult();

        var_dump($result); die();

        //$qb = $dem->createQueryBuilder();
        //$q = $qb->update(Entity\Poll\Relation::class, 'pr');
        ////    ->set('u.username', $qb->expr()->literal($username))
        ////    ->set('u.email', $qb->expr()->literal($email))
        ////    ->where('u.id = ?1')
        ////    ->setParameter(1, $editId)
        ////    ->getQuery();
        ////$p = $q->execute();
        //
        /////** @var Entity\Poll\Relation $poll */
        ////$poll = $entity->getPoll();
        ////$poll->setTotal(1 + $poll->getTotal());
        ////
        ////$dem->merge($poll);
        ////$dem->flush();
    }
}
