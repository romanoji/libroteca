<?php
declare(strict_types=1);

namespace Helper;

trait ClearsBetweenScenarios
{
    /**
     * Need this, because some scenarios use steps from multiple contexts
     * and as we want (in scenario scope) to load data from the same repository instance
     * we share them in SharedObjects between multiple contexts, but after this
     * we need to cleanup the repositories to start new, fresh scenario.
     *
     * @AfterScenario
     */
    public function clearRepositories()
    {
        SharedObjects::clearSharedObjects();
    }
}
