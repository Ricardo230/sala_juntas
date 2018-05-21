<?php

namespace AppBundle\Repository;

/**
 * SalaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SalaRepository extends \Doctrine\ORM\EntityRepository
{
	public static function salasDisponibles(SalaRepository $repository)
    {
        return $repository->createQueryBuilder('c')
            ->where("c.flagActiva = 1")
            ->orderBy('c.nombre');
    }
}
