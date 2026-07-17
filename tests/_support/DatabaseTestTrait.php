<?php

namespace Tests\Support;

trait DatabaseTestTrait
{
    use \CodeIgniter\Test\DatabaseTestTrait {
        migrateDatabase as private ci4MigrateDatabase;
    }

    protected function migrateDatabase(): void
    {
        if ($this->migrate === false) {
            return;
        }

        if ($this->namespace === null) {
            $this->migrations->setNamespace(null);
            $result = $this->migrations->latest('tests');
            self::$doneMigration = true;

            if ($result !== true) {
                throw new \RuntimeException('Migration failed');
            }
        } else {
            $this->ci4MigrateDatabase();
        }
    }
}
