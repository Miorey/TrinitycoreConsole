<?php
/**
 * Created by PhpStorm.
 * User: clem
 * Date: 06/12/18
 * Time: 15:37
 */

namespace App\Controller\Api;


use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\TrinityCore\TrinityClient;
use Psr\Log\LoggerInterface;

class AccountController extends FOSRestController
{
    private $trinityClient;

    public function __construct(TrinityClient $trinityClient)
    {
        $this->setTrinityClient($trinityClient);
    }


    /**
     * @param LoggerInterface $logger
     * @param Request $request
     * @param string $account
     * @param string $oldPassword
     * @param string $newPassword
     * @return View
     * @Rest\Get("/account/{account}/{oldPassword}/{newPassword}")
     */
    public function getAccount(LoggerInterface $logger,  Request $request, string $account, string $oldPassword, string $newPassword) : View {
        $logger->info('Hello world');
        $command = "account set password {$account} {$newPassword} {$newPassword}";
        $logger->info($command);
        $ret = $this->getTrinityClient()->executeCommand($command);
        return View::create($ret, Response::HTTP_ACCEPTED);
    }

    /**
     * @return TrinityClient
     */
    public function getTrinityClient()
    {
        return $this->trinityClient;
    }

    /**
     * @param TrinityClient $trinityClient
     */
    public function setTrinityClient($trinityClient): void
    {
        $this->trinityClient = $trinityClient;
    }

}