<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\RestructuredText\LaTeX\Renderers;

use phpDocumentor\Guides\RestructuredText\Nodes\ImageNode;
use phpDocumentor\Guides\RestructuredText\Renderers\NodeRenderer;
use phpDocumentor\Guides\RestructuredText\TemplateRenderer;

class ImageNodeRenderer implements NodeRenderer
{
    /** @var ImageNode */
    private $imageNode;

    /** @var TemplateRenderer */
    private $templateRenderer;

    public function __construct(ImageNode $imageNode, TemplateRenderer $templateRenderer)
    {
        $this->imageNode        = $imageNode;
        $this->templateRenderer = $templateRenderer;
    }

    public function render() : string
    {
        return $this->templateRenderer->render('image.tex.twig', [
            'imageNode' => $this->imageNode,
        ]);
    }
}
