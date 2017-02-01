<?php

namespace Kodosunifa;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

/**
 * Class SniffCodesCommand
 *
 * @author Jem Raymundo jraymundo.yrockdev@gmail.com
 *
 * @package Kodosunifa
 */
class SniffCodesCommand extends Command
{
    use SniffHelper;

    const STANDARD_CODE = 'PSR2';

    /**
     * Exclude sniff list
     *
     * @var array
     */
    protected $invalidDirectories = ['test', 'tests', 'storage', 'build', 'vendor', 'public', 'resources', 'database'];

    protected function configure()
    {
        $this->setName('kodosunifa:sniff')->setDescription('Sniff code into psr-2 based');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $directoriesToTest = $this->getDirectoriesToTest();
        $gitCurrentBranch = $this->getCurrentBranch();

        $this->setSnifferInformation($io, [
                'STANDARD: ' => self::STANDARD_CODE,
                'DIRECTORIES: ' => $directoriesToTest,
                'GIT BRANCH: ' => $gitCurrentBranch
            ]);

        $process = new Process('phpcs -n --standard=' . self::STANDARD_CODE . ' ' . $directoriesToTest);
        $process->run();
        $sniffResult = $process->getOutput();

        if ($sniffResult) {
            $io->text($sniffResult);
            $this->handleMessages($io, 'error', 'STYLE CHECK PLEASE FIX BEFORE PROCEEDING!');
            exit(1);
        }

        $this->handleMessages($io, 'success', sprintf('STYLE CHECK PASSED PUSHING TO BRANCH %s', $gitCurrentBranch));
        exit(0);
    }

    /**
     * @return string
     */
    private function getCurrentBranch()
    {
        $branch = new Process('git rev-parse --abbrev-ref HEAD');
        $branch->run();

        return $branch->getOutput();
    }

    /**
     * Helper method to get directories needed to tes
     * @return string
     */
    private function getDirectoriesToTest()
    {
        return $this->removeInvalidDirAndImplodeValidDir($this->getApplicationDirectories());
    }

    /**
     * Removes not needed directories based on the invalidDirectories list
     *
     * @param $collectedDirectoryBaseName
     * @return string
     */
    private function removeInvalidDirAndImplodeValidDir($collectedDirectoryBaseName)
    {
        $validDirectories = array_diff($collectedDirectoryBaseName, $this->invalidDirectories);
        $implodedDirectories = implode('/ ', $validDirectories);

        return $implodedDirectories . '/';
    }

    /**
     * Get all directories(only) from the app root
     *
     * @return array
     */
    private function getApplicationDirectories()
    {
        $collectedDirectoryBaseName = [];
        $directories = glob(__DIR__ . '/../../../../*', GLOB_ONLYDIR);

        foreach ($directories as $directory) {
            $collectedDirectoryBaseName[] = basename($directory);
        }

        return $collectedDirectoryBaseName;
    }
}
