<?php

namespace BahaaAlhagar\ShoppingCart;

use Illuminate\Console\Command;

class makeShopCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:shop {--config= : whether you want to publish the config file or not}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a simple shop for a quick start.';

    /**
     * Package Helper class
     * @var object
     */
    protected $helper;

    /**
     * command folder paths
     * @var object
     */
    protected $paths;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(packageHelper $helper)
    {
        parent::__construct();

        $this->paths = [
        '\\\\Shop\\\\Models'      => app_path('Shop'),
        '\\\\Shop\\\\Views'       => resource_path('views\Shop'),
        '\\\\Shop\\\\Migrations'  => database_path('migrations'),
        '\\\\Shop\\\\Requests'    => app_path('Http\Requests\Shop'),
        '\\\\Shop\\\\Controllers' => app_path('Http\Controllers\Shop'),
        ];

        $this->helper = $helper;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('building the shop.');

        $this->info('moving the nessecary files.');

        foreach($this->paths as $from => $to)
        {
            $this->helper->files->copyDirectory(__DIR__.$from, $to);
        }

        $this->helper->dumpAutoloads();

        $this->info("Your quick shop is done! check it ".config('app.url'). "/shop.");
    }
}
