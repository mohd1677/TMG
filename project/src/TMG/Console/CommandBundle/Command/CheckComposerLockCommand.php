<?php

namespace TMG\Console\CommandBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class CheckComposerLockCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tools:check-composer-lock')
            ->setDescription('Is composer.lock file up to date with composer.json');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $composerJson = $this->getContainer()->get('kernel')->getRootDir()."/../composer.json";

        $composerLock = $this->getContainer()->get('kernel')->getRootDir()."/../composer.lock";

        $json = md5_file($composerJson);

        $lock = json_decode(file_get_contents($composerLock))->hash;

        if ($json != $lock) {
            $output->writeLn("<error>composer.lock is not up-to-date with composer.json</error>");

            return 1;
        }

        $output->writeln('<info>composer.lock is up-to-date with composer.json</info>');

        return 0;
    }
}
