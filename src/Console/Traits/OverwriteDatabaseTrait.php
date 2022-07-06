<?php

namespace ZnDatabase\Base\Console\Traits;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use ZnLib\Components\Http\Helpers\UrlHelper;
use ZnCore\Container\Helpers\ContainerHelper;
use ZnCore\DotEnv\Domain\Libs\DotEnv;
use ZnCore\Text\Helpers\TextHelper;
use ZnDatabase\Base\Domain\Repositories\Eloquent\SchemaRepository;

trait OverwriteDatabaseTrait
{

    /*protected function defaultExclideHosts(): array {
        return [
//            'localhost',
//            '127.0.0.1',
        ];
    }*/

    protected function defaultExclideDatabaseNames(): array
    {
        return [
//            'localhost:*_test',
//            'localhost:*',
//            "pgsql://postgres:postgres@localhost/social_server",
//            'pgsql://localhost/social_server',
//            'pgsql://localhost/*',


//            "*://*@localhost/*_test",
//            "*://localhost/*_test",
//            "sqlite:*test*",

//            "*://*localhost/*",
//            'sqlite:*',
        ];
    }

    protected function isExcludeDatabaseNames(string $database): bool
    {
        $exclude = DotEnv::get('DATABASE_PROTECT_EXCLUDE', null);
        $exclude = !empty($exclude) ? explode(',', $exclude) : $this->defaultExclideDatabaseNames();
//        dd($exclude);
        foreach ($exclude as $ex) {
            $ex = trim($ex);
            if ($ex == $database || fnmatch($ex, $database)) {
                return true;
            }
        }
        return false;
    }

    protected function getConnectionUrl(): string
    {
        /** @var SchemaRepository $schemaRepository */
        $schemaRepository = ContainerHelper::getContainer()->get(SchemaRepository::class);
        $connection = $schemaRepository->getConnection();

        $params = [
            'scheme' => $connection->getConfig('driver'),
            'host' => $connection->getConfig('host') ?: 'localhost',
            'port' => $connection->getConfig('port'),
            'path' => $connection->getConfig('database'),
            'user' => $connection->getConfig('username'),
            'pass' => $connection->getConfig('password'),
        ];

        if ($connection->getConfig('driver') == 'sqlite') {
            unset($params['host']);
            unset($params['user']);
            unset($params['pass']);
        }

        $url = UrlHelper::generateUrlFromParams($params);
        if ($connection->getConfig('driver') == 'sqlite') {
            $url = TextHelper::removeDoubleChar($url, '/');
        }

        return $url;
    }

    protected function isContinue(InputInterface $input, OutputInterface $output): bool
    {
        $url = $this->getConnectionUrl();
        if ($this->isExcludeDatabaseNames($url)) {
            return true;
        }

//        dd($this->dbRepository->getConnection()->getConfig('database'));

        $output->writeln('');
        $output->writeln("Connection URL: <fg=green>{$url}</>");
        $output->writeln('');
        $output->writeln('Further actions may overwrite your database!');
        $question = new ConfirmationQuestion('Do you want to continue? (y|N): ', false);
        $helper = $this->getHelper('question');
        $isContinue = $helper->ask($input, $output, $question);
        $output->writeln('');
        return $isContinue;
    }
}
