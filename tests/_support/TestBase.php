<?php

namespace Tests\Support;

use CodeIgniter\Database\MigrationRunner;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use Config\Database;
use Config\Migrations;

abstract class TestBase extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $DBGroup = 'tests';

    protected function setUpDatabase(): void
    {
        $this->loadDependencies();
        $this->setUpMigrate();
        $this->setUpSeed();
    }
}
