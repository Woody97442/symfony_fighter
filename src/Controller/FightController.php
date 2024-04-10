<?php

namespace App\Controller;

use App\Entity\Fight;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\UserChampion;

#[Route('/api')]
class FightController extends AbstractController
{
    #[Route('/fight', name: 'app_fight', methods: ['POST'])]
    public function fight(EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], 400);
        }

        $enemy = $em
            ->getRepository(User::class)
            ->findAll()[array_rand($em->getRepository(User::class)->findAll())];

        if (!$enemy) {
            return new JsonResponse(['error' => 'Enemy not found'], 400);
        }

        $userChampion = $em
            ->getRepository(UserChampion::class)
            ->findOneBy(['user' => $user->getId()]);

        $enemyChampion = $em
            ->getRepository(UserChampion::class)
            ->findOneBy(['user' => $enemy->getId()]);


        $result = runFight($userChampion, $enemyChampion, $em);

        return new JsonResponse(['message' => $result], 201);
    }
}

function runFight($user, $enemy, $em)
{
    $battleLog = [];
    $user_pv = $user->getPv();
    $enemy_pv = $enemy->getPv();
    $roundNumber = 0;

    while ($user_pv > 0 && $enemy_pv > 0) {
        $roundNumber++;
        $attacker = mt_rand(0, 1) == 0 ? $user : $enemy;
        $defender = $attacker === $user ? $enemy : $user;

        $damage = mt_rand(10, $attacker->getPower());

        if ($defender === $user) {
            $user_pv -= $damage;
            $defender_pv = $user_pv;
        } else {
            $enemy_pv -= $damage;
            $defender_pv = $enemy_pv;
        }

        $battleLog[] = [
            'attacker' => $attacker->getChampion()->getName() . ' de ' . $attacker->getUser()->getUsername(),
            'defender' => $defender->getChampion()->getName() . ' de ' . $defender->getUser()->getUsername(),
            'damage' => $damage,
            'attacker_pv' => $attacker === $user ? $user_pv : $enemy_pv,
            'defender_pv' => $defender_pv,
        ];

        if ($defender_pv <= 0) {
            break;
        }

        [$user_pv, $enemy_pv] = [$enemy_pv, $user_pv];
        [$user, $enemy] = [$enemy, $user];
    }

    $winner = $user_pv <= 0 ? $enemy->getUser()->getUsername() : ($enemy_pv <= 0 ? $user->getUser()->getUsername() : 'No one');

    $winnerId = $user_pv <= 0 ? $enemy->getUser() : ($enemy_pv <= 0 ? $user->getUser() : null);

    $resultRunFight = [
        'battle_log' => $battleLog,
        'winner' => $winner,
        'rounds' => $roundNumber,
    ];

    // Create a new Champion
    $historicFight = new Fight();
    $historicFight->setUser1($user->getUser());
    $historicFight->setUser2($enemy->getUser());
    $historicFight->setWinner($winnerId);
    $historicFight->setCreatedAt(new \DateTimeImmutable());

    $em->persist($historicFight);
    $em->flush();


    return $resultRunFight;
}
