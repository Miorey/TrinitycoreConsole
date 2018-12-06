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

class AccountController extends FOSRestController
{
    private $trinityClient;

    public function __construct(TrinityClient $trinityClient)
    {
        $this->setTrinityClient($trinityClient);
    }

    /**
     * @param Request $request
     * @param string $account
     * @param string $oldPassword
     * @param string $newPassword
     * @return View
     * @Rest\Get("/account/{account}/{oldPassword}/{newPassword}")
     * @throws \App\TrinityCore\Exceptions\SoapException
     */
    public function getAccount(Request $request, string $account, string $oldPassword, string $newPassword) : View {
        $client = new TrinityClient($account, $oldPassword);
        $command = "account password {$account} {$oldPassword} {$newPassword} {$newPassword}";
        $ret = $client->executeCommand($command);
        return View::create($ret, Response::HTTP_ACCEPTED);
    }

    public function putAccount(Request $request) {

    }

    public function postAccount(Request $request) {

    }

    /**
     * @param Request $request
     * @return View
     * @Rest\Get("/accounts/online")
     */
    public function getOnline(Request $request){
        $ret = $this->getTrinityClient()->executeCommand('account onlinelist');
        return View::create($ret, Response::HTTP_OK);
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