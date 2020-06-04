<?php
namespace App\Strategies;

use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

class CustomStrategy implements VersionStrategyInterface
{
    private $version;

    public function __construct(?string $customParam)
    {
        $this->version = !is_null($customParam) ? md5($customParam) : date('Ymd');
    }

    public function getVersion($path)
    {
        return $this->version;
    }

    public function applyVersion($path)
    {
        return sprintf('%s?v=%s', $path, $this->getVersion($path));
    }
}