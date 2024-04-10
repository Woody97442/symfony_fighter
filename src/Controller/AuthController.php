<?php

namespace App\Controller;

use App\Entity\Champion;
use App\Entity\User;
use App\Entity\UserChampion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class AuthController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $password = $data['password'];
        $username = $data['username'];

        // Check if the request is valid
        if (empty($email) || empty($password) || empty($username)) {
            return new JsonResponse(['error' => 'Invalid request'], 400);
        }

        // Validate the email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new JsonResponse(['error' => 'Invalid email'], 400);
        }

        // Validate the password
        if (strlen($password) < 6) {
            return new JsonResponse(['error' => 'Password must be at least 6 characters long'], 400);
        }

        // Check if the user already exists with email or username
        $user = $em
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if ($user) {
            return new JsonResponse(['error' => 'User already exists'], 400);
        }

        $user = $em
            ->getRepository(User::class)
            ->findOneBy(['username' => $username]);

        if ($user) {
            return new JsonResponse(['error' => 'Username already exists'], 400);
        }

        // Create a new user
        $user = new User();
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setUsername($username);
        $user->setRoles(['ROLE_USER']);

        $em->persist($user);

        if ($user->getRoles() != ["ROLE_ADMIN"]) {
            $Champions = $em
                ->getRepository(Champion::class)
                ->findAll();

            $randomChampion = $Champions[rand(0, count($Champions) - 1)];
            $championPv = $randomChampion->getPv();
            $championPower = $randomChampion->getPower();

            // Create a new user
            $UserChampion = new UserChampion();
            $UserChampion->setUser($user);
            $UserChampion->setChampion($randomChampion);
            $UserChampion->setPv(rand(250, $championPv));
            $UserChampion->setPower(rand(10, $championPower));

            $em->persist($UserChampion);
        }

        $em->flush();

        return new JsonResponse(['message' => 'User created'], 201);
    }
}
