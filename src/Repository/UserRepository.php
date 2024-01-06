<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByUsernameOrEmail(?string $name, ?string $email): ?int
    {
        $result = $this->getEntityManager()
            ->createQueryBuilder()
                ->select("u.id")
                    ->from('App\Entity\User','u')
                        ->andWhere("u.name = :name")
                            ->orWhere("u.email = :email")
                                ->setMaxResults(1)
                                    ->setParameters(["name"=>$name,"email"=>$email])
                                        ->getQuery()
                                            ->getOneOrNullResult();
        return $result['id'] ?? null;
    }
}
