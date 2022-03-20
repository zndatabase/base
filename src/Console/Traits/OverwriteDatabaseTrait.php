<?php

namespace ZnDatabase\Base\Console\Traits;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use ZnCore\Base\Libs\App\Helpers\ContainerHelper;
use ZnCore\Base\Libs\DotEnv\DotEnv;
use ZnDatabase\Base\Domain\Repositories\Eloquent\SchemaRepository;

trait OverwriteDatabaseTrait
{

    /*protected function defaultExclideHosts(): array {
        return [
//            'localhost',
//            '127.0.0.1',
        ];
    }*/

    protected function defaultExclideDatabaseNames(): array {
        return [
//            'localhost:*_test',
            'localhost:*',
        ];
    }
    
    /*protected function isExcludeHost(string $host): bool {
        $exclude = DotEnv::get('DUMP_EXCLUDE_HOSTS', $this->defaultExclideHosts());
        $isExclude = in_array($host, $exclude);
        return $isExclude;
    }*/
    
    protected function isExcludeDatabaseNames(string $database): bool {
        $exclude = DotEnv::get('DUMP_EXCLUDE_DATABASES', null);
        $exclude = !empty($exclude) ? explode(',', $exclude) : $this->defaultExclideDatabaseNames();
        foreach ($exclude as $ex) {
            if ($ex == $database || fnmatch($ex, $database)) {
                return true;
            }
        }
        return false;
    }
    
    protected function isContinue(InputInterface $input, OutputInterface $output): bool
    {
        /** @var SchemaRepository $schemaRepository */
        $schemaRepository = ContainerHelper::getContainer()->get(SchemaRepository::class);
        $connection = $schemaRepository->getConnection();

        $host = $connection->getConfig('host');
        /*if ($this->isExcludeHost($host)) {
            return true;
        }*/

        $database = $connection->getConfig('database');
        if ($this->isExcludeDatabaseNames($host . ':' . $database)) {
            return true;
        }

        /*$isExclude = in_array($database, $exclude);
        if($isExclude) {
            return true;
        }*/

//        dd($this->dbRepository->getConnection()->getConfig('database'));

        $output->writeln('');
        $output->writeln('Further actions may overwrite your database!');
        $question = new ConfirmationQuestion('Do you want to continue? (y|N): ', false);
        $helper = $this->getHelper('question');
        $isContinue = $helper->ask($input, $output, $question);
        $output->writeln('');
        return $isContinue;
    }
}
