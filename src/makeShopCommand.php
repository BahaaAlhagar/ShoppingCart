<?php

namespace BahaaAlhagar\ShoppingCart;

use Illuminate\Console\Command;
use BahaaAlhagar\ShoppingCart\packageHelper;

class makeShopCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:shop --vendor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a simple shop for a quick start.';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $helper;

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
