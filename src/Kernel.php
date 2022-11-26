<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use FOS\UserBundle\FOSUserBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Twig\Extra\TwigExtraBundle\TwigExtraBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use Symfony\Bundle\MakerBundle\MakerBundle; //dev
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\WebpackEncoreBundle\WebpackEncoreBundle;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    
    public function registerBundles(): array
    {
        // $contents = require $this->getProjectDir().'/config/bundles.php';
        // $f = [];
        // foreach ($contents as $class => $envs) {
        //     if (isset($envs['all']) || isset($envs[$this->environment])) {
        //         $f[] = $class();
        //     }
        // }

        // return $f;

        return [
            new FrameworkBundle(),
            new MonologBundle(),
            new SensioFrameworkExtraBundle(),
            new TwigBundle(),
            new TwigExtraBundle(),
            new DoctrineBundle(),
            new DoctrineMigrationsBundle(),
            new MakerBundle(), //dev
            new SecurityBundle(),
            new FOSUserBundle(),
            new WebpackEncoreBundle(),
        ];
    }
}
