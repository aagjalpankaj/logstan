<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

#[AsCommand(name: 'analyse')]
class AnalyseCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Analysing logs...</info>');

        // Get the path to our custom configuration
        $configPath = $this->getConfigPath();

        // Build the command
        $command = [
            './vendor/bin/phpstan',
            'analyse',
            'app',
            '--configuration='.$configPath,
            '--ansi',
        ];

        // Create and run the process
        $process = new Process($command);
        $process->setTimeout(null);

        $output->writeln('<comment>Running: '.implode(' ', $command).'</comment>');
        $output->writeln('');

        $process->run(function ($type, $buffer) use ($output): void {
            $output->write($buffer);
        });

        return $process->isSuccessful() ? Command::SUCCESS : Command::FAILURE;
    }

    private function getConfigPath(): string
    {
        return 'vendor/aagjalpankaj/logstan/phpstan.neon';
    }
}
