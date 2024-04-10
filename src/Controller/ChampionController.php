<?php

namespace App\Controller;

use App\Entity\Champion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted("ROLE_ADMIN")]
class ChampionController extends AbstractController
{
    #[Route('/champion/add', name: 'app_champion', methods: ['POST'])]
    public function createChampion(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $champion_name = $data['name'];
        $champion_pvmax = intval($data['pvmax']);
        $champion_powermax = intval($data['powermax']);

        // Check if the request is valid
        if (empty($champion_name) || empty($champion_pvmax) || empty($champion_powermax)) {
            return new JsonResponse(['error' => 'Invalid request'], 400);
        }

        // Create a new Champion
        $champion = new Champion();
        $champion->setName($champion_name);
        $champion->setPv($champion_pvmax);
        $champion->setPower($champion_powermax);

        $em->persist($champion);
        $em->flush();


        return new JsonResponse(['message' => 'Champion created'], 201);
    }
}
