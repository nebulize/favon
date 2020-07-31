<?php

namespace Favon\Application\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Facades\Module as ModuleFacade;
use Nwidart\Modules\Module;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Report\PHP;

/**
 * @codeCoverageIgnore
 */
class DuskServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        //
    }

    /**
     * Register services.
     *
     * @throws \RuntimeException
     */
    public function register(): void
    {
        if (!$this->app->runningInConsole() && $this->app->environment() === 'testing') {
            try {
                $this->triggerCoverage();
            } catch (\Exception $exception) {
                Log::info('Dusk coverage: '.$exception->getMessage());
            }
        }
    }

    /**
     * Gather code coverage data from dusk tests.
     */
    private function triggerCoverage()
    {
        if (\extension_loaded('xdebug') === false) {
            return;
        }
        $coverage = new CodeCoverage();
        $filter = $coverage->filter();
        $filter->addDirectoryToWhitelist(app_path());
        $modulesList = ModuleFacade::all();
        /** @var Module $module */
        foreach ($modulesList as $module) {
            $path = $module->getPath();
            $filter->removeDirectoryFromWhitelist($path.'/Tests');
            $filter->removeDirectoryFromWhitelist($path.'/Resources');
            $filter->removeDirectoryFromWhitelist($path.'/Routes');
            $filter->removeDirectoryFromWhitelist($path.'/Database');
            $filter->removeDirectoryFromWhitelist($path.'/Config');
        }

        $name = microtime(true).'.'.str_random(8);
        $coverage->start($name);
        $this->app->terminating(function () use ($coverage, $name) {
            $coverage->stop();
            $writer = new PHP();
            $dir = storage_path('coverage');
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            $writer->process($coverage, "{$dir}/{$name}.php.cov");
        });
    }
}
