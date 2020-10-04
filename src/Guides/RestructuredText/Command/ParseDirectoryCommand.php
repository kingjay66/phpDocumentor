<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\RestructuredText\Command;

use phpDocumentor\Guides\RestructuredText\Kernel;

final class ParseDirectoryCommand
{
    /** @var Kernel */
    private $kernel;
    private $directory;
    private $outputDirectory;

    public function __construct(Kernel $kernel, string $directory, string $outputDirectory)
    {
        $this->kernel = $kernel;
        $this->directory = $directory;
        $this->outputDirectory = $outputDirectory;
    }

    public function getKernel(): Kernel
    {
        return $this->kernel;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function getOutputDirectory(): string
    {
        return $this->outputDirectory;
    }
}
