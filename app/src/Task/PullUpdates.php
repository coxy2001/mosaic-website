<?php

namespace Mosiac\Website\Task;

use SilverStripe\Dev\BuildTask;

class PullUpdates extends BuildTask
{
    private static $segment = "pull-updates";

    protected $title = "Pull Updates";
    protected $description = "Pull updates from git repo";
    protected $enabled = true;

    public function run($request)
    {
        echo "<h3>Running: git pull</h3>";
        echo shell_exec("cd .. && git pull");
        echo "<h3>Running: composer install</h3>";
        echo shell_exec("cd .. && composer install");

        echo "<h3>Running: perms</h3>";
        echo shell_exec("cd .. && find . -type d -exec chmod 0775 {} +");
        echo "Folders updated <br>";
        echo shell_exec("cd .. && find . -type f -exec chmod 0664 {} +");
        echo "Files updated <br>";
        echo shell_exec("cd .. && chown -R www-data:www-data .");
        echo "Ownership updated <br>";
    }
}
