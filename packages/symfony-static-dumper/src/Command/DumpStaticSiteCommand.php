<?php

declare(strict_types=1);

namespace Symplify\SymfonyStaticDumper\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symplify\PackageBuilder\Console\Command\CommandNaming;
use Symplify\PackageBuilder\Console\ShellCode;
use Symplify\SymfonyStaticDumper\Application\SymfonyStaticDumperApplication;

final class DumpStaticSiteCommand extends Command
{
    /**
     * @var string
     */
    private $publicDirectory;

    /**
     * @var string
     */
    private $outputDirectory;

    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;

    /**
     * @var SymfonyStaticDumperApplication
     */
    private $symfonyStaticDumperApplication;

    public function __construct(
        SymfonyStaticDumperApplication $symfonyStaticDumperApplication,
        SymfonyStyle $symfonyStyle,
        ParameterBagInterface $parameterBag
    ) {
        parent::__construct();

        $this->publicDirectory = $parameterBag->get('kernel.project_dir') . '/public';
        $this->outputDirectory = getcwd() . '/output';

        $this->symfonyStyle = $symfonyStyle;
        $this->symfonyStaticDumperApplication = $symfonyStaticDumperApplication;
    }

    protected function configure(): void
    {
        $this->setName(CommandNaming::classToName(self::class));
        $this->setDescription('Dump website to static HTML and CSS in the output directory');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->symfonyStyle->section('Dumping static website');
        $this->symfonyStaticDumperApplication->run($this->publicDirectory, $this->outputDirectory);

        $this->symfonyStyle->note('Run local server to see the output: "php -S localhost:8001 -t output"');

        return ShellCode::SUCCESS;
    }
}
