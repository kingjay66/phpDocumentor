<?php

declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link https://phpdoc.org
 */

namespace phpDocumentor\Transformer\Writer\Twig;

use League\CommonMark\ConverterInterface;
use phpDocumentor\Descriptor\ProjectDescriptor;
use phpDocumentor\Guides\Graphs\Twig\UmlExtension;
use phpDocumentor\Guides\Twig\AssetsExtension;
use phpDocumentor\Path;
use phpDocumentor\Transformer\Template;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\ChainLoader;
use Twig\Loader\FilesystemLoader;

class EnvironmentFactory
{
    /** @var LinkRenderer */
    private $renderer;

    /** @var ?Path */
    private $templateOverridesAt;

    /** @var ConverterInterface */
    private $markDownConverter;
    private AssetsExtension $assetsExtension;
    private UmlExtension $umlExtension;

    public function __construct(
        LinkRenderer $renderer,
        ConverterInterface $markDownConverter,
        AssetsExtension $assetsExtension,
        UmlExtension $umlExtension
    ) {
        $this->renderer = $renderer;
        $this->markDownConverter = $markDownConverter;
        $this->assetsExtension = $assetsExtension;
        $this->umlExtension = $umlExtension;
    }

    public function withTemplateOverridesAt(Path $path): void
    {
        $this->templateOverridesAt = $path;
    }

    public function create(
        ProjectDescriptor $project,
        Template $template
    ): Environment {
        $mountManager = $template->files();

        $loaders = [];
        if ($this->templateOverridesAt instanceof Path) {
            $loaders[] = new FilesystemLoader([(string) $this->templateOverridesAt]);
        }

        $loaders[] = new FlySystemLoader($mountManager->getFilesystem('template'), '', 'base');
        $loaders[] = new FlySystemLoader($mountManager->getFilesystem('templates'));
        $loaders[] = new FlySystemLoader($mountManager->getFilesystem('guides'), '', 'guides');

        $env = new Environment(new ChainLoader($loaders));

        $this->addPhpDocumentorExtension($project, $env);
        $this->enableDebug($env);

        return $env;
    }

    /**
     * Adds the phpDocumentor base extension to the Twig Environment.
     */
    private function addPhpDocumentorExtension(
        ProjectDescriptor $project,
        Environment $twigEnvironment
    ): void {
        $extension = new Extension($project, $this->markDownConverter, $this->renderer);
        $twigEnvironment->addExtension($extension);
        $twigEnvironment->addExtension($this->assetsExtension);
        $twigEnvironment->addExtension($this->umlExtension);
    }

    private function enableDebug(Environment $twigEnvironment): void
    {
        $twigEnvironment->setCache(false);
        $twigEnvironment->enableDebug();
        $twigEnvironment->enableAutoReload();
        $twigEnvironment->addExtension(new DebugExtension());
    }
}
