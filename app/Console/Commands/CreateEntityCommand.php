<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PHP_CodeSniffer\Tokenizers\PHP;

class CreateEntityCommand extends Command
{
    protected $signature = 'create:entity {entity}';
    protected $description = 'Create new hole entity';
    protected $filesMap = [
        'Business' => [
            'url' => '/Businesses/',
            'files' => [
                '{{entityCaps}}AddBusiness' => 'business.add',
                '{{entityCaps}}DeadDetailBusiness' => 'business.dead_detail',
                '{{entityCaps}}DeadListBusiness' => 'business.dead_list',
                '{{entityCaps}}DeleteBusiness' => 'business.delete',
                '{{entityCaps}}DetailBusiness' => 'business.detail',
                '{{entityCaps}}EditBusiness' => 'business.edit',
                '{{entityCaps}}ListBusiness' => 'business.list',
            ],
        ],
        'Controller' => [
            'url' => '/Http/Controllers/',
            'files' => [
                '{{entityCaps}}AddController' => 'controller.add',
                '{{entityCaps}}DeadDetailController' => 'controller.dead_detail',
                '{{entityCaps}}DeadListController' => 'controller.dead_list',
                '{{entityCaps}}DeleteController' => 'controller.delete',
                '{{entityCaps}}DetailController' => 'controller.detail',
                '{{entityCaps}}EditController' => 'controller.edit',
                '{{entityCaps}}ListController' => 'controller.list',
            ],
        ],
        'Queries' => [
            'url' => '/Queries/',
            'files' => [
                '{{entityCaps}}AddQueries' => 'queries.add',
                '{{entityCaps}}CommonQueries' => 'queries.common',
                '{{entityCaps}}DeadDetailQueries' => 'queries.dead_detail',
                '{{entityCaps}}DeadListQueries' => 'queries.dead_list',
                '{{entityCaps}}DeleteQueries' => 'queries.delete',
                '{{entityCaps}}DetailQueries' => 'queries.detail',
                '{{entityCaps}}EditQueries' => 'queries.edit',
                '{{entityCaps}}ListQueries' => 'queries.list',
            ],
        ],
        'Repository' => [
            'url' => '/Repositories/',
            'files' => [
                '{{entityCaps}}AddRepository' => 'repository.add',
                '{{entityCaps}}CommonRepository' => 'repository.common',
                '{{entityCaps}}DeadDetailRepository' => 'repository.dead_detail',
                '{{entityCaps}}DeadListRepository' => 'repository.dead_list',
                '{{entityCaps}}DeleteRepository' => 'repository.delete',
                '{{entityCaps}}DetailRepository' => 'repository.detail',
                '{{entityCaps}}EditRepository' => 'repository.edit',
                '{{entityCaps}}ListRepository' => 'repository.list',
            ],
        ],
        'Route' => [
            'url' => '/routes/',
            'files' => [
                '{{entityOriginal}}_routes' => 'route',
            ],
        ],
        'TestBusiness' => [
            'url' => '/tests/unit/Business/',
            'files' => [
                '{{entityCaps}}AddBusinessTest' => 'test.business.add',
                '{{entityCaps}}DeadDetailBusinessTest' => 'test.business.dead_detail',
                '{{entityCaps}}DeadListBusinessTest' => 'test.business.dead_list',
                '{{entityCaps}}DeleteBusinessTest' => 'test.business.delete',
                '{{entityCaps}}DetailBusinessTest' => 'test.business.detail',
                '{{entityCaps}}EditBusinessTest' => 'test.business.edit',
                '{{entityCaps}}ListBusinessTest' => 'test.business.list',
            ],
        ],
        'TestRepository' => [
            'url' => '/tests/unit/Repositories/',
            'files' => [
                '{{entityCaps}}AddRepositoryTest' => 'test.repository.add',
                '{{entityCaps}}CommonRepositoryTest' => 'test.repository.common',
                '{{entityCaps}}DeadDetailRepositoryTest' => 'test.repository.dead_detail',
                '{{entityCaps}}DeadListRepositoryTest' => 'test.repository.dead_list',
                '{{entityCaps}}DeleteRepositoryTest' => 'test.repository.delete',
                '{{entityCaps}}DetailRepositoryTest' => 'test.repository.detail',
                '{{entityCaps}}EditRepositoryTest' => 'test.repository.edit',
                '{{entityCaps}}ListRepositoryTest' => 'test.repository.list',
            ],
        ],
        'Validator' => [
            'url' => '/Validators/Concrete/',
            'files' => [
                '{{entityCaps}}AddValidator' => 'validator.add',
                '{{entityCaps}}EditValidator' => 'validator.edit',
                '{{entityCaps}}ListValidator' => 'validator.list',
            ],
        ],
        'Migration' => [
            'url' => '/database/migrations/',
            'files' => [
                '{{dateTime}}create_{{entityOriginal}}_table' => 'table.create',
            ],
        ],
    ];
    protected $fromRoot = [
        'TestBusiness',
        'TestRepository',
    ];

    /**
     * Create a new command instance
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Get the console command arguments
     * @return array
     */
    protected function getArguments()
    {
        $arguments = [
            ['entity', InputArgument::REQUIRED, 'Entity name.'],
        ];
        return $arguments; 
    }

    /**
    * Get the stub file for the generator.
    * @return string
    */
    protected function getStubContent(
        $name,
        $stub
    ) {
        $content = file_get_contents(base_path('app').'/Console/Stubs/'.$name.'/'.$stub.'.stub');
        return $content;
    }

    /**
     * Create folder if not exists
     * @param string $path
     * @param string $entityCaps
     * @param string $name
     * @return void
     */
    private function createFolder(
        string $path,
        string $entityCaps,
        string $name
    ) {
        if ($name != 'Route' && $name != 'Migration') {
            $holePath = base_path('app').$path.$entityCaps;
            if (in_array($name, $this->fromRoot)) {
                $holePath = base_path().$path.$entityCaps;
            }
            if (!file_exists($holePath)) {
                mkdir($holePath, 0777, true);
            }
        }
    }

    /**
     * Prepare entity name
     * @param string $entity
     * @return string entity
     */
    private function prepareEntityName(
        string $entity
    ) {
        $entity = strtolower($entity);
        if (strpos($entity, '_') !== false) {
            $entityArray = explode('_', $entity);
            $name = '';
            foreach ($entityArray as $partialName) {
                if (empty($name)) {
                    $name .= $partialName;
                    continue;
                }
                $name .= ucfirst($partialName);
            }
            return $name;
        }
        return $entity;
    }

    /**
     * Create file with contents
     * @param string $path
     * @param string $name
     * @param string $contents
     * @return void
     */
    private function createFile(
        string $path,
        string $entityCaps,
        string $nameStub,
        string $contents,
        string $name
    ) {
        $holePath = base_path('app').$path.$entityCaps.'/'.$nameStub;
        if ($name == 'Route' || $name == 'Migration') {
            $holePath = base_path().$path.$nameStub;
        }
        if (in_array($name, $this->fromRoot)) {
            $holePath = base_path().$path.$entityCaps.'/'.$nameStub;
        }
        if (!file_exists($holePath.'.php')) {
            $file = fopen($holePath.'.php', 'wr');
            fwrite($file, $contents);
            fclose($file);
        } 
    }

    /**
     * Addd new route to route list file
     * @param string $entityOriginal
     * @param string $entityCaps
     * @return void
     */
    private function addRoutes($entityOriginal, $entityCaps)
    {
        $lines = file(base_path('app').'/../bootstrap/list_routes.php'); 
        $last = sizeof($lines) - 1 ; 
        unset($lines[$last]); 
        
        $file = fopen(base_path('app').'/../bootstrap/list_routes.php', 'w'); 
        fwrite($file, implode('', $lines)); 
        fclose($file); 
        
        $newLine = "    'App\Http\Controllers\\".$entityCaps."' => '".$entityOriginal."',";
        $myFile = file_put_contents(base_path('app').'/../bootstrap/list_routes.php', $newLine.PHP_EOL , FILE_APPEND | LOCK_EX);
        $myFile = file_put_contents(base_path('app').'/../bootstrap/list_routes.php', '];'.PHP_EOL , FILE_APPEND | LOCK_EX);
    }

    /**
     * Execute the console command
     * @return mixed
     */
    public function handle()
    {
        $dateTime = date('Y_m_d_His_');
        $entityOriginal = strtolower($this->argument('entity'));

        $validEntity = preg_match('/^[a-z_]+$/', $entityOriginal);
        if (!$validEntity) {
            $this->error('Entity name must have only lowercase letters and underscore!');
        }

        $entity = $this->prepareEntityName($this->argument('entity'));
        $entityCaps = ucfirst($entity);

        $this->addRoutes($entityOriginal, $entityCaps);

        foreach ($this->filesMap as $name => $info) {
            $this->info('');
            $this->info('Creating '.$name.'...');
            $this->createFolder(
                $info['url'],
                $entityCaps,
                $name
            );

            foreach ($info['files'] as $nameStub => $stub) {
                $nameStub = str_replace('{{entityOriginal}}', $entityOriginal, $nameStub);
                $nameStub = str_replace('{{entity}}', $entity, $nameStub);
                $nameStub = str_replace('{{entityCaps}}', $entityCaps, $nameStub);
                $nameStub = str_replace('{{dateTime}}', $dateTime, $nameStub);
                $content = $this->getStubContent(
                    $name,
                    $stub
                );
                $content = str_replace('{{entityOriginal}}', $entityOriginal, $content);
                $content = str_replace('{{entity}}', $entity, $content);
                $content = str_replace('{{entityCaps}}', $entityCaps, $content);

                $this->info('Creating file '.$nameStub.'...');
                $this->createFile(
                    $info['url'],
                    $entityCaps,
                    $nameStub,
                    $content,
                    $name
                );
            }
        }
        $this->info('');
        $this->info('All done!');
    }
}