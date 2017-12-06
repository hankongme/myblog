<?php namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\Tools\AuthTool;
class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
 * Bootstrap the application events.
 *
 * @return void
 */
    public function boot()
    {

        $this->bladeDirectives();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {


    }

    /**
     * Register the blade directives
     *
     * @return void
     */
    private function bladeDirectives()
    {
        
        \Blade::directive('can', function ($expression) {
            return "<?php if (\\AuthTool::can({$expression})):?>";
        }
        );
        \Blade::directive('endcan', function ($expression) {
            return "<?php endif;  ?>";
        }
        );

    }


}
