<?php


namespace Ecjia\System\Providers;


use Royalcms\Component\Database\Schema\Blueprint;
use Royalcms\Component\Support\Facades\Schema;
use Royalcms\Component\Support\Fluent;
use Royalcms\Component\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->registerSchemaMacros();
    }

    /**
     * Register the schema macros.
     */
    protected function registerSchemaMacros()
    {
        Blueprint::macro('dropIndexIfExists', function(string $index): Fluent {
            if ($this->hasIndex($index)) {
                return $this->dropIndex($index);
            }

            return new Fluent();
        });

        Blueprint::macro('hasIndex', function(string $index): bool {
            $conn = Schema::getConnection();
            $dbSchemaManager = $conn->getDoctrineSchemaManager();
            $doctrineTable = $dbSchemaManager->listTableDetails($this->getTable());

            return $doctrineTable->hasIndex($index);
        });
    }

}