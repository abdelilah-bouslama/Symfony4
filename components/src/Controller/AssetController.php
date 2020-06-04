<?php

namespace App\Controller;

use App\Strategies\CustomStrategy;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\UrlPackage;
use Symfony\Component\Asset\PathPackage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\Asset\VersionStrategy\StaticVersionStrategy;
use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;

class AssetController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index()
    {
        return $this->render('asset/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/assets-empty", name="home.assets.empty")
     *
     * @return Response
     */
    public function getEmptyAsset():Response
    {
        $package = new Package(new EmptyVersionStrategy());
        return $this->render('asset/assets.html.twig', [
            'controller_name' => 'HomeController',
            'asset_path' => $package->getUrl('main.css')
        ]);
    }

    /**
     * Append the v1 suffix to any asset path, with specific format
     * 
     * @Route("/assets-static/{version}/{format}", name="home.assets.static")
     * @param string $format ('%s?version=%s' => /image.png?version=v1, '%2$s/%1$s' => v1/image.png)
     * 
     * @return Response
     */
    public function getStaticAsset(int $version, $format=''):Response
    {
        $package = new Package(new StaticVersionStrategy('v'.$version, "%s?$format%s"));

        return $this->render('asset/assets.html.twig', [
            'controller_name' => 'HomeController',
            'asset_path' => $package->getUrl('main.css')
        ]);
    }

    /**
     * @Route("/assets-manifest", name="home.assets.manifest")
     *
     * @return Response
     */
    public function getManifestAsset(KernelInterface $kernel):Response
    {
        $package = new Package(new JsonManifestVersionStrategy($kernel->getProjectDir().'/rev-manifest.json'));

        return $this->render('asset/assets.html.twig', [
            'controller_name' => 'HomeController',
            'asset_path' => $package->getUrl('css/app.css')
        ]);
    }

    /**
     * @Route("/assets-grouped/{version}/{format}", name="home.assets.grouped")
     *
     * @return Response
     */
    public function getGroupedAsset(int $version = 1, $format = ''):Response
    {
        $package = new PathPackage('assets/css/', new StaticVersionStrategy('v'.$version, $format));

        return $this->render('asset/assets.html.twig', [
            'controller_name' => 'HomeController',
            'asset_path' => $package->getUrl('app.css')
        ]);
    }

    /**
     * asset context using PathPackage
     * @Route("/assets-context/{version}", name="home.assets.context")
     *
     * @return Response
     */
    public function getContextAsset(int $version = 1, RequestStack $request):Response
    {
        $package = new PathPackage(
            'assets/css/', 
            new StaticVersionStrategy('v'.$version), 
            new RequestStackContext($request)
        );

        return $this->render('asset/assets.html.twig', [
            'controller_name' => 'HomeController',
            'asset_path' => $package->getUrl('app.css')
        ]);
    }

    /**
     * @Route("/assets-cdn-single/{version}", name="home.assets.cdn.single")
     *
     * @return Response
     */
    public function getSingleCdnAsset(int $version = 1):Response
    {
        $package = new UrlPackage(
            'http://myapp/public/assets/css/',  //we can remove http:(schema-agnostic)
            new StaticVersionStrategy('v'.$version)
        );

        return $this->render('asset/assets.html.twig', [
            'controller_name' => 'HomeController',
            'asset_path' => $package->getUrl('app.css')
        ]);
    }

    /**
     * @Route("/assets-cdn-multiple/{version}", name="home.assets.cdn.multiple")
     *
     * @return Response
     */
    public function getMultipleCdnAsset(int $version = 1):Response
    {
        $package = new UrlPackage(
            [
                'http://myapp/public/assets/css/',
                'http://myapp2/public/assets/css/',
            ], 
            new StaticVersionStrategy('v'.$version)
        );

        return $this->render('asset/assets.html.twig', [
            'controller_name' => 'HomeController',
            'asset_path' => $package->getUrl('app.css')
        ]);
    }
    
    /**
     * asset context using UrlPackage
     * @Route("/assets-url-context/{version}", name="home.assets.url.context")
     *
     * @return Response
     */
    public function getUrlContextAsset(int $version = 1, RequestStack $request):Response
    {
        $package = new UrlPackage(
            [
                'http://myapp/public/assets/css/',
                'http://myapp2/public/assets/css/',
            ], 
            new StaticVersionStrategy('v'.$version), 
            new RequestStackContext($request)
        );

        return $this->render('asset/assets.html.twig', [
            'controller_name' => 'HomeController',
            'asset_path' => $package->getUrl('app.css')
        ]);
    }

    /**
     * asset context using UrlPackage
     * @Route("/assets-packages/{version}", name="home.assets.name.package")
     *
     * @return Response
     */
    public function getPackagesAsset(int $version = 1, RequestStack $request):Response
    {
        $versionStrategy = new StaticVersionStrategy('v'.$version);
        $defaultPackage = new Package($versionStrategy);
        $namedPackages = array(
            'css' => new UrlPackage('http://img.example.com/', $versionStrategy),
            'doc' => new PathPackage('/somewhere/deep/for/documents', $versionStrategy),
        );

        $packages = new Packages($defaultPackage, $namedPackages);

        return $this->render('asset/assets.html.twig', [
            'controller_name' => 'HomeController',
            'asset_path' => 'DOC : '. $packages->getUrl('word.doc', 'doc') .
            ', CSS : ' . $packages->getUrl('app.css', 'css') 
        ]);
    }

    /**
     * Create custom strategy
     * @Route("/asset-custom-strategy/{customParam}", name="assets.custom.strategy")
     */
    public function applyCustomStrategy(?string $customParam):Response
    {
        $customStrategy = new CustomStrategy($customParam);
        $package = new Package($customStrategy);
        return $this->render('asset/assets.html.twig', [
            'controller_name' => 'HomeController',
            'asset_path' => $package->getUrl('app.css')
        ]);
    }
}