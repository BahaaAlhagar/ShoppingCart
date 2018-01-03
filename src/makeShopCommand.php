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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(packageHelper $helper)
    {
        parent::__construct();

        $this->helper = $helper;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('making the shop for you!');
    }
}
