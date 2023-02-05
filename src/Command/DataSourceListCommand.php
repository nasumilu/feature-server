<?php

namespace App\Command;

use App\Service\Database;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:data-source:list',
    description: 'Add a short description for your command',
)]
class DataSourceListCommand extends Command
{
    public function __construct(private readonly Database $connection)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'Pagination limit', 10)
            ->addOption('page', 'p', InputOption::VALUE_OPTIONAL, 'Pagination page', 1);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $options = ['limit' => 10, 'page' => 0];
        foreach ($options as $option => $default) {
            $optionValue = $input->getOption($option);
            if (!is_numeric($optionValue) && $optionValue <= 0) {
                $message = 'Expected a numeric %s greater than zero, found %s!';
                $io->error(sprintf($message, $option, $limit ?? 'none'));
                $optionValue = $io->ask('Please provide a valid limit: ', $default, function ($value) use ($option, $message) {
                    if (!is_numeric($value) && $value <= 0) {
                        throw new \RuntimeException(sprintf($message, $option, $value ?? 'none'));
                    }
                    return $value;
                });
                $input->setOption($option, $optionValue);
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $page = $input->getOption('page');
        $limit = $input->getOption('limit');
        do {
            $results = $this->connection->listDatasources($page++, $limit);
            $io->table(['ID', 'Dbname', 'Username', 'Host', 'Port', 'Driver'], $results['results']);
            if ($results['next'] && !$io->confirm('Next')) {
                break;
            }
        } while($results['next']);
        return Command::SUCCESS;
    }
}
