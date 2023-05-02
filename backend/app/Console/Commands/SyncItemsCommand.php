<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Repositories\Contracts\ClassOneCheckRepositoryInterface;
use App\Services\Item\SyncItemsService;
use Carbon\Carbon;

class SyncItemsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sync_items_command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '商品データの同期処理';

    private $syncItemsService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SyncItemsService $syncItemsService)
    {
        $this->syncItemsService = $syncItemsService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "\n";
        echo date("Y-m-d H:i:s"). " {$this->description}(sync_items_command) start \n";

        $synced_items = $this->syncItemsService->sync();

        foreach($synced_items as $index=>$item) {
            echo ($index + 1) . " synced jan={$item->jan} from EC site \n";
        }
        
        echo "Total = {$synced_items->count()} \n";
        echo date("Y-m-d H:i:s"). " {$this->description}(sync_items_command) finish \n";
        echo "\n";
    }
}
