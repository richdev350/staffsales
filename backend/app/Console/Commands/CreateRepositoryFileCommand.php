<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateRepositoryFileCommand extends Command
{
    /**
     * @const string repository dir path
    */
    const CONTRACTS_PATH = 'app/Models/Repositories/Contracts';
    const ELOQUENT_PATH = 'app/Models/Repositories/Eloquent';

    const CONTRACTS_NAMESPACE = 'app\Models\Repositories\Contracts';
    const ELOQUENT_NAMESPACE = 'app\Models\Repositories\Eloquent';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {repositoryName : The name of repository}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create repository files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * @var string
     */
    private $file_name;

    /**
     * @var string
     */
    private $repository_file_name;

    /**
     * @var string
     */
    private $interface_file_name;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
    */
    public function handle()
    {
        $this->file_name = $this->argument('repositoryName');

        if (is_null($this->file_name)) {
            $this->error('Repository Name invalid');
        }

        $this->repository_file_name = self::ELOQUENT_PATH . "/" . $this->file_name . 'Repository.php';
        $this->interface_file_name = self::CONTRACTS_PATH . "/" . $this->file_name . 'RepositoryInterface.php';
        if ($this->isExistFiles()) {
            $this->error('already exist');
            return;
        }

        $this->creatRepositoryFile();
        $this->createInterFaceFile();
        $this->info('create successfully');
    }

    /**
     * Repositoryのfileを作成する
     * @return void
     */
    private function creatRepositoryFile(): void
    {
        $content = "<?php\ndeclare(strict_types=1);\n\nnamespace " . self::ELOQUENT_NAMESPACE . ";\n\nuse Throwable;\nuse Exception;\nuse Illuminate\Database\Eloquent\Builder;\nuse Illuminate\Support\Collection;\nuse Illuminate\Support\Facades\DB;\nuse Illuminate\Pagination\LengthAwarePaginator;\nuse App\Models\Entities\\" . $this->file_name . ";\nuse App\Models\Repositories\Contracts\\" . $this->file_name . "RepositoryInterface;\nuse App\Models\Repositories\Eloquent\Repository;\n\nfinal class " . $this->file_name . "Repository extends Repository implements $this->file_name" . "RepositoryInterface\n{\n    protected static \$model = " . $this->file_name . "::class;\n}\n";
        file_put_contents($this->repository_file_name, $content);
    }

    /**
     * Interfaceのfileを作成する
     * @return void
     */
    private function createInterFaceFile(): void
    {
        $content = "<?php\ndeclare(strict_types=1);\n\nnamespace " . self::CONTRACTS_NAMESPACE . ";\n\nuse Illuminate\Support\Collection;\nuse Illuminate\Pagination\LengthAwarePaginator;\nuse App\Models\Entities\\" . $this->file_name . ";\n\ninterface " . $this->file_name . "RepositoryInterface\n{\n}\n";
        str_replace("/", "\\", $content);
        file_put_contents($this->interface_file_name, $content);
    }

    /**
     * 同名fileの確認
     * @return bool
     */
    private function isExistFiles(): bool
    {
        return file_exists($this->repository_file_name) && file_exists($this->interface_file_name);
    }
}
