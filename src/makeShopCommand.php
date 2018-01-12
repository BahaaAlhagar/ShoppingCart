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
     * command routes to be add when making the shop
     * @var object
     */
    protected $routes;


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
        '\\\\Shop\\\\Seeds'       => database_path('seeds'),
        '\\\\Shop\\\\Views'       => resource_path('views\Shop'),
        '\\\\Shop\\\\Migrations'  => database_path('migrations'),
        '\\\\Shop\\\\Requests'    => app_path('Http\Requests\Shop'),
        '\\\\Shop\\\\Controllers' => app_path('Http\Controllers\Shop'),
        ];

        $this->routes = "
        /*shop routes*/
    route::group(['prefix' => 'shop','namespace' => 'Shop'], function(){

    route::get('/', 'ProductController@index')->name('product.shop');

    route::get('addtocart/{product}/{qty?}', 'ProductController@addToCart')->name('product.addToCart');

    route::get('reduce/{id}', 'ProductController@reduceOneItem')->name('product.reduceOneItem');
    route::get('remove/{id}', 'ProductController@RemovefromCart')->name('product.RemovefromCart');

    route::patch('modify/{id}', 'ProductController@modify')->name('product.modify');

    route::get('cart', 'ProductController@shoppingCart')->name('product.shoppingCart');

    route::get('checkout', 'OrderController@checkOut')->name('order.checkOut');
    route::post('checkout', 'OrderController@payment')->name('order.payment');
});";

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

        // check if shop already configured before
        if(file_exists(storage_path('shop_installed.txt')))
        {
            $this->error('the shop already installed!');

            $this->error("to reinstall it please remove or rename this file in your storage ".storage_path('shop_installed.txt'));

            return false;
        }

        $this->info('moving the necessary files.');

        foreach($this->paths as $from => $to)
        {
            $this->helper->files->copyDirectory(__DIR__.$from, $to);
        }

        $this->helper->files->copy(__DIR__.'\\Shop\\item.png', storage_path('app/public/item.png'));

        $this->info('updating User model!');

        $replacedUserText = 'use Notifiable;
        public function orders() {return $this->hasMany(\App\Shop\Order::class);}';

        $this->helper->replaceAndSave(app_path('User.php'), 'use Notifiable;', $replacedUserText);

        $this->info('added User class relations.');

        $this->info('updating routes/web.php!');

        $this->helper->files->append(base_path('routes/web.php'), $this->routes);

        $this->info('added the shop routes successfully.');

        $this->info('moving shop_installed.txt file to the storage folder.');

        $this->helper->files->copy(__DIR__.'\\Shop\\shop_installed.txt', storage_path('shop_installed.txt'));

        $this->helper->storageLink();
        $this->helper->dumpAutoloads();

        $this->info("Your quick shop is done! check it ".config('app.url'). "/shop.");
    }
}
