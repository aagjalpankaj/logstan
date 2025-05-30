<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

use function Laravel\Prompts\spin;

#[AsCommand(name: 'insights')]
class InsightsCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setDescription('Get insights about log usage')
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
            '--configuration=vendor/aagjalpankaj/logstan/insights.neon',
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
            }, message: 'Gathering log insights...');

        if ($input->getOption('debug')) {
            $output->write($process->getOutput());
        }

        $this->displayInsights($output);

        return $process->isSuccessful() ? Command::SUCCESS : Command::FAILURE;
    }

    private function displayInsights(OutputInterface $output): void
    {
        $statsFile = sys_get_temp_dir().'/logstan/log_stats.json';

        if (! file_exists($statsFile)) {
            $output->writeln('<error>No log statistics found. Run the analysis first.</error>');

            return;
        }

        $stats = json_decode(file_get_contents($statsFile), true);

        if (empty($stats)) {
            $output->writeln('<info>No log calls found in the analyzed code.</info>');

            return;
        }

        $totalCalls = 0;
        $methodCounts = [];

        foreach ($stats as $fileStats) {
            $totalCalls += $fileStats['total'];

            if (isset($fileStats['methods'])) {
                foreach ($fileStats['methods'] as $method => $count) {
                    if (! isset($methodCounts[$method])) {
                        $methodCounts[$method] = 0;
                    }
                    $methodCounts[$method] += $count;
                }
            }
        }

        $output->writeln('');
        $output->writeln('<info>📊 Log Usage Insights</info>');
        $output->writeln('');
        $output->writeln(sprintf('Total log calls: <comment>%d</comment>', $totalCalls));
        $output->writeln('');

        $output->writeln('<info>Log Level Distribution:</info>');

        $table = new Table($output);
        $table->setHeaders(['Log Level', 'Count', 'Percentage']);

        arsort($methodCounts);
        foreach ($methodCounts as $method => $count) {
            $percentage = round(($count / $totalCalls) * 100, 2);
            $table->addRow([$method, $count, $percentage.'%']);
        }

        $table->render();

        $output->writeln('');

        $output->writeln('<info>All Context Keys:</info>');

        $contextKeyCounts = [];
        foreach ($stats as $fileStats) {
            if (isset($fileStats['contextKeys'])) {
                foreach ($fileStats['contextKeys'] as $key => $count) {
                    if (! isset($contextKeyCounts[$key])) {
                        $contextKeyCounts[$key] = 0;
                    }
                    $contextKeyCounts[$key] += $count;
                }
            }
        }

        arsort($contextKeyCounts);

        $table = new Table($output);
        $table->setHeaders(['Context Key', 'Count']);

        foreach ($contextKeyCounts as $key => $count) {
            $table->addRow([$key, $count]);
        }

        $table->render();

        $output->writeln('');
        $output->writeln(sprintf('Total unique context keys: <comment>%d</comment>', count($contextKeyCounts)));

        $output->writeln('');
        $output->writeln('<info>Tips to Reduce Context Keys:</info>');
        $output->writeln('- Reduce the number of context keys by consolidating similar data.');
        $output->writeln('- Avoid using different keys to log the same data.');
        $output->writeln('- Implement a logging policy to limit the number of context keys.');
    }
}
