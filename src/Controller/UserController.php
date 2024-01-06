<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserService $service;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    #[Route('/login/', name: 'user_login', methods:'POST')]
    public function index(Request $request): JsonResponse
    {
        try{
            $data = [];
            parse_str($request->getContent(),$data);
            $result = $this->service->validateLogin($data);
        }catch(\Exception $e){
            $result = 
            [
                'error' => "Internal server error",
                'message' => $e->getMessage(),
            ];
        }

        return $this->json($result);
    }

    #[Route('/create/', name: 'user_create')]
    public function create(Request $request): JsonResponse
    {
        try{
            $data = [];
            parse_str($request->getContent(),$data);
            $result = $this->service->save($data);
        }catch(\Exception $e){
            $result = 
            [
                'error' => "Internal server error",
                'message' => $e->getMessage(),
            ];
        }

        return $this->json($result);
    }

    #[Route('/delete/', name: 'user_delete')]
    public function delete(Request $request): JsonResponse
    {
        $this->service->delete($request->get('id'));
        return $this->json(true);
    }
}
