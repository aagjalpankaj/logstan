<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\spin;

#[AsCommand(name: 'analyse')]
class AnalyseCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setDescription('Analyse logs')
            ->addArgument(
                'directory',
                InputArgument::OPTIONAL,
                'Source directory to analyse',
                'app'
            )
            ->addOption(
                'debug',
                'd',
                InputOption::VALUE_NONE,
                'Enable debug mode (disables caching as well)'
            )
            ->addOption(
                'memory-limit',
                'm',
                InputOption::VALUE_REQUIRED,
                'Set the memory limit',
                '-1'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $command = [
            './vendor/bin/phpstan',
            'analyse',
            $input->getArgument('directory'),
            '--configuration=vendor/aagjalpankaj/logstan/analyse.neon',
            '--memory-limit='.$input->getOption('memory-limit'),
            '--no-progress',
            '--ansi',
        ];

        if ($input->getOption('debug')) {
            $command[] = '--debug';
        }

        $process = new Process(
            command: $command,
            env: array_merge($_ENV, ['COLUMNS' => '200']),
            timeout: null
        );

        if ($input->getOption('debug')) {
            $output->writeln('<comment>Command:</comment> '.implode(' ', $command));
        }

        $output->writeln('');

        spin(
            callback: function () use ($process): void {
                $process->start();

                while ($process->isRunning()) {
                    usleep(100000);
                }
            }, message: 'Analysing logs...');

        $output->write($process->getOutput());

        return $process->isSuccessful() ? Command::SUCCESS : Command::FAILURE;
    }
}
