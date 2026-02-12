<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\Service;

use Scaffold\CoreBundle\ScaffoldCoreBundle;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;

class ManifestManager
{
    public function __construct(
        private readonly ScaffoldCoreBundle $bundle,
        #[Autowire(param: 'kernel.project_dir')]
        private readonly string $projectRootDirectory,
    ) {}

    public function sync(OutputInterface $output, bool $forceOverwrite): void
    {
        $fileSystem = new FileSystem();
        $manifestFile = $this->bundle->getPath() . '/../manifest/manifest.json';

        /**
         * @var non-empty-list<array{
         *   src: string,
         *   dest: non-empty-list<array{
         *     target: string,
         *     action: 'copy'|'overwrite'
         *   }>
         * }> $mappings
         */
        $mappings = json_decode($fileSystem->readFile($manifestFile), true, 512, JSON_THROW_ON_ERROR);

        $fileMap = [];
        foreach ($mappings as $mappingGroup) {
            foreach ($mappingGroup['files'] as $file) {
                $source = $this->bundle->getPath() . '/../manifest/' . $file['src'];
                $target = $this->projectRootDirectory . '/' . $file['src'];

                // Default for overwrite is false
                $overwrite = $forceOverwrite || ($file['overwrite'] ?? false);
                // Default for including a dist file is false
                $includeDistFile = $file['dist'] ?? false;

                $fileMap[$target] = [
                    'source' => $source,
                    'canOverwrite' => $overwrite,
                ];

                if ($includeDistFile === true) {
                    $target .= '.dist';
                    $fileMap[$target] = [
                        'source' => $source,
                        'canOverwrite' => true,
                    ];
                }
            }
        }

        $changeMap = [
            'copy' => [],
            'review' => [],
            'skipped' => [],
        ];

        $output->writeln("\nSyncing files from manifest...");
        foreach ($fileMap as $target => $details) {
            $source = $details['source'];
            $canOverwrite = $details['canOverwrite'];

            if (file_exists($target)) {
                if (filesize($source) === filesize($target) && md5_file($source) === md5_file($target)) {
                    $changeMap['skipped'][] = 'No changes between '.$source.' and '.$target;

                    // Contents are identical so we can skip this file
                    continue;
                }

                if (!$canOverwrite) {
                    // We're not allowed to overwrite the file so skip onto next one
                    $changeMap['review'][] = 'Changed detected between ' . $source . ' ' . $target . "\n";
                    $changeMap['review'][] = '    review changes with `diff ' . $source . ' ' . $target . "`\n";
                    continue;
                }
            }

            $changeMap['copy'][] = 'COPY - ' . $source . ' => ' . $target;
            $fileSystem->copy($source, $target, true);
        }

        foreach ($changeMap as $type => $changes) {
            if (empty($changes)) {
                continue;
            }
            $output->writeln("\n## ". strtoupper($type) . " ##\n" . implode("\n", $changes));
        }

        $output->writeln('');
        $output->writeln('Files have been skipped as the target file already existed.');
        $output->writeln('To overwrite existing files, run `bin/console scaffold:sync-manifest --force`');
    }
}
