<?php

namespace App\Controller;

use App\Entity\Device;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Cdf\BiCoreBundle\Controller\FiController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Device controller.
 */
class DeviceController extends FiController
{
    /**
     * Matches / exactly.
     *
     * @Route("/Device/List", name="Device_List", options={"expose"=true} )
     */
    public function getDevices(Request $request, EntityManagerInterface $em): JsonResponse
    {

        $ret = [];
        $devices = $em->createQueryBuilder()
                ->select('d')
                ->from(Device::class, 'd')
                ->getQuery()
                ->getResult()
        ;

        foreach ($devices as $device) {
            $ret[] = [
                "id" => $device->getId(),
                "address" => $device->getAddress(),
                "name" => $device->getName()
            ];
        }


        return new JsonResponse($ret);
    }
}
