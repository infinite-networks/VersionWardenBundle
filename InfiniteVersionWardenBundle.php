<?php

/**
 * This file is part of the Infinite VersionWardenBundle project.
 *
 * (c) Infinite Networks Pty Ltd <http://www.infinite.net.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Infinite\VersionWardenBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class InfiniteVersionWardenBundle extends Bundle
{
    public function boot()
    {
        // This should run whenever debugging is enabled (in dev but not prod).
        // In emergencies, this can be bypassed from parameters.yml.

        if ($this->container->getParameter('kernel.debug') && !$this->container->getParameter('infinite_version_warden.bypass')) {
            $projectDir = $this->container->getParameter('kernel.project_dir');

            // Load PHP version constraint from composer.lock
            $lockFilename = $projectDir . '/composer.lock';

            $composerLock = json_decode(file_get_contents($lockFilename));
            $constraint = $composerLock->platform->php;

            // Constraints must be formatted as "5.6.*" or "~5.6.23".
            // Extract the "5.6" part.
            if (!preg_match('/^(\d+\.\d+).\*$/', $constraint, $matches) &&
                !preg_match('/^\~(\d+\.\d+).\d+$/', $constraint, $matches)) {

                // Throwing an exception here might not result in console output, depending on display_errors.
                // Print and die manually.

                echo "Could not parse composer.lock version constraint. It must be formatted like '5.6.*' or '~7.1.1'\n";
                exit(1);
            }

            // Compare to the currently running version.
            $requiredVersion = $matches[1];
            $runningVersion  = sprintf('%s.%s', PHP_MAJOR_VERSION, PHP_MINOR_VERSION);

            if ($runningVersion !== $requiredVersion) {

                // Throwing an exception here might not result in console output, depending on display_errors.
                // Print and die manually.

                echo(sprintf(
                    "This project requires PHP %s, but is running as PHP %s\n",
                    $requiredVersion,
                    $runningVersion
                ));
                exit(1);
            }
        }
    }
}
