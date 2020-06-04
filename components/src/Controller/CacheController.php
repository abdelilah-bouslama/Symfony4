<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CacheController extends AbstractController
{

    const DEFAULT_PARAM = [
        'controller_name' => 'HomeController',
    ];

    /**
     * @Route("psr-6-filesystem-single/{cacheName}/{action}/{value}", name="cache.psr6_filesystem", defaults={"value" = null})
     *
     * @return Response
     */
    public function getFileSystemCache(string $cacheName, string $action, string $value, int $ttl = 60):Response
    {
        $cache = new FilesystemCache();
        $msg = "";
        if (!$cache->has($cacheName) && $action == 'set') {
            $cache->{$action}($cacheName, $value, $ttl);
            $msg = "cache $cacheName with $value is saved";
        }elseif($action == 'delete') {
            $cache->{$action}($cacheName);
            $msg = "cache $cacheName is deleted";
        } elseif ($action == 'get') {
            $msg = "cache $cacheName is : ".$cache->{$action}($cacheName);
        }

        //clear all cache
        //$cache->clear();

        return$this->render(
            'cache\index.html.twig',
            array_merge(self::DEFAULT_PARAM, ["msg" => $msg])
        );
    }
   
    /**
     * @Route("psr-6-filesystem-mutiple", name="cache.psr6_filesystem")
     *
     * @return Response
     */
    public function getFileSystemCacheMutiple():Response
    {

        return$this->render(
            'cache\index.html.twig',
            array_merge(self::DEFAULT_PARAM, ["msg" => $msg])
        );
    }
}