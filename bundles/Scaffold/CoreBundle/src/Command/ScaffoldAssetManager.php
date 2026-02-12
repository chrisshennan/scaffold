<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Command;

use Scaffold\CoreBundle\Service\ManifestManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'scaffold:asset-manager:sync')]
class ScaffoldAssetManager extends Command
{
    public function __construct(
        private readonly ManifestManager $manifestManager,
    ) {
        parent::__construct();
    }

    public function __invoke(
        OutputInterface $output,
        #[Option(name: 'force', shortcut: 'f')]
        bool $forceOverwrite = false,
    ): int
    {
        $this->manifestManager->sync($output, $forceOverwrite);

        return Command::SUCCESS;
    }
}
