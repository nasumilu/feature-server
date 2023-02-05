<?php

namespace App\Command;

use App\Service\Database;
use App\Service\EncryptInterface;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Nasumilu\DBAL\Driver\PostGISMiddleware;
use Nasumilu\DBAL\Driver\SpatialMiddleware;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:data-source:add',
    description: 'Add a short description for your command',
)]
class DataSourceAddCommand extends Command
{

    public function __construct(private readonly Database $connection)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('driver', null, InputOption::VALUE_OPTIONAL, 'The database driver')
            ->addOption('dbname', 'd', InputOption::VALUE_OPTIONAL, 'The name of the database')
            ->addOption('username', 'u', InputOption::VALUE_OPTIONAL, 'The database connection username')
            ->addOption('host', 'H', InputOption::VALUE_OPTIONAL, 'The database host or IP address')
            ->addOption('port', 'p', InputOption::VALUE_OPTIONAL, 'The database port');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $driver = $this->resolveDriverOptions($input->getOption('driver'), $io);
        $params = [
            'driver' => $driver,
            'dbname' => $this->resolveDbname($input->getOption('dbname'), $io),
            'user' => $this->resolveUsername($input->getOption('username'), $io),
            'host' => $this->resolveHost($input->getOption('host'), $io),
            'port' => $this->resolvePort($input->getOption('port'), $io),
            'password' => $this->resolvePassword($io),
        ];
        $config = new Configuration();
        $config->setMiddlewares([$this->resolveMiddleware($driver)]);
        // attempt connection
        $connection = null;
        try {
            $connection = DriverManager::getConnection($params, $config);
            $connection->connect();
            $io->info("Connection successful!");
        } catch (Exception $e) {
            $io->warning($e->getMessage());
            return Command::FAILURE;
        } finally {
            $connection?->close();
        }
        // store in database here
        $this->connection->insertDatasource($params);


        return Command::SUCCESS;
    }

    private function resolveMiddleware(string $driver): SpatialMiddleware {
        return match($driver) {
            'pgsql', 'pdo_pgsql' => new PostGISMiddleware(),
            default => throw new \RuntimeException('Unable to resolve spatial platform for driver ' . $driver . '!')
        };
    }

    private function resolvePassword(SymfonyStyle $io): string
    {
        $question = new Question('Please enter the password: ');
        $question->setHidden(true)
            ->setHiddenFallback(false)
            ->setValidator(function ($value) {
                if (empty($value)) {
                    throw new \RuntimeException("Password can not be empty!");
                }
                return $value;
            });
        $question->setMaxAttempts(2);
        $password = $io->askQuestion($question);

        $question = new Question('Reenter the password: ');
        $question->setHiddenFallback(false)
            ->setHidden(true)
            ->setValidator(function ($value) use ($password) {
                if ($value !== $password) {
                    throw new \RuntimeException("The password does not match!");
                }
                return $value;
            });
        $question->setMaxAttempts(3);
        return $io->askQuestion($question);
    }

    private function resolvePort(string | int | null $port, SymfonyStyle $io): int
    {
        if (is_numeric($port)) {
            return (int)$port;
        }

        $question = new Question('Please enter the database port: ');
        $question->setValidator(function ($value) {
            if (!is_numeric($value)) {
                throw new \RuntimeException('Database port MUST be numeric, found ' . $value . '!');
            }
            return $value;
        });
        $question->setMaxAttempts(2);
        return $io->askQuestion($question);
    }

    private function resolveHost(string | null $host, SymfonyStyle $io): string
    {
        if (null !== $host) {
            return $host;
        }
        $question = new Question('Please enter the database host: ');
        return $io->askQuestion($question);
    }

    private function resolveUsername(string | null $username, SymfonyStyle $io): string
    {
        if (null !== $username) {
            return $username;
        }
        $question = new Question('Please enter the username: ');
        return $io->askQuestion($question);

    }

    private function resolveDbname(string | null $dbname, SymfonyStyle $io): string
    {
        if (null !== $dbname) {
            return $dbname;
        }
        $question = new Question('Please enter the database name: ');
        return $io->askQuestion($question);
    }

    private function resolveDriverOptions(string | null $driver, SymfonyStyle $io): string
    {
        $drivers = DriverManager::getAvailableDrivers();
        $allowedDriver = in_array($driver, $drivers);
        if ($allowedDriver) {
            return $driver;
        }
        $question = new ChoiceQuestion('Please select a driver', $drivers);
        $question->setErrorMessage('Must be a valid PDO driver');
        return $io->askQuestion($question);
    }
}
