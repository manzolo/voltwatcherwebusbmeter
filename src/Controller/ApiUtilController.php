<?php

namespace App\Controller;

use App\Entity\Settings;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class ApiUtilController extends AbstractController
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/api/get/settings/app.json", name="app_api_appgetsettings", methods={"GET"})
     */
    public function appGetSettingsAction(): JsonResponse
    {
        $qb = $this->em->createQueryBuilder()
                ->select('s')
                ->from(Settings::class, 's')
                ->getQuery();
        $settings = $qb->getResult();
        $newsettings = [];
        foreach ($settings as $setting) {
            $newsettings[$setting->getKey()] = $setting->getValue();
        }
        return $this->json($newsettings);
    }
    /**
     * @Route("/api/get/server/datetime.json", name="app_api_appserverdatetime", methods={"GET"})
     */
    public function appServerDatetimeAction(): JsonResponse
    {
        $now = new DateTime();
        return $this->json([
                    'datetime' => $now->format('Y-m-d H:i:s'),
                    'date' => $now->format('Y-m-d'),
                    'time' => $now->format('H:i:s')
        ]);
    }
}
