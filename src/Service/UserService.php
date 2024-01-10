<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\SodiumPasswordHasher;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class UserService
{
    private EntityManagerInterface $em;
    private ServiceEntityRepository $repo;
    private PasswordHasherInterface $hasher;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->em = $entityManagerInterface;
        $this->hasher = new SodiumPasswordHasher();
        $this->repo = $this->em->getRepository(User::class);        
    }

    public function validateLogin(?array $data): ?array
    {
        if((empty($data['name']) && empty($data['password'])) || (empty($data['email']) && empty($data['password']))) return ['result'=>false];

        $id = $this->getIdByUsernameOrEmail($data['name']??null, $data['email']??null);
        $user = $this->repo->find($id ?? 0);
        if(empty($user)) return ['result'=>false];
        
        return ['result'=>$this->hasher->verify($user->getPassword(),$data['password']),'user'=>$user->getId()];
    }

    public function save(?array $data): ?array
    {
        if(empty($data['name']) || empty($data['email']) || empty($data['password'])) ['error'=>'Empty data passed in'];

        if(!empty($data['id'])){
            $object = $this->repo->find($data['id']);
            $object->setName($data['name']);
            $object->setEmail($data['email']);
            $object->setPassword($this->hasher->hash($data['password']));
        }else{
            if(!empty($this->getIdByUsernameOrEmail($data['name'],$data['email']))) return ['error'=>'Email or username is already taken'];
            $object = new User($data['name'],$data['email'],$this->hasher->hash($data['password']));
            $object->setImage($data['image'] ?? null);
        }

        $this->em->persist($object);
        $this->em->flush();

        return ['user'=>$object->getId()];
    }

    public function delete(?int $id): void
    {
        if(empty($id)) return;
        $object = $this->repo->find($id);

        $this->em->remove($object);
        $this->em->flush();
    }

    private function getIdByUsernameOrEmail(?string $name = null, ?string $email = null): ?int
    {
        if(empty($name) && empty($email)) return null;
        return $this->repo->findByUsernameOrEmail($name,$email);
    }
}