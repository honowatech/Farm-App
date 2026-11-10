<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Activitylog\ActivityLogStatus;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Désactive le journal d'audit en test : la table activity_log
        // n'existe pas dans la base SQLite en mémoire.
        app(ActivityLogStatus::class)->disable();
    }
}
