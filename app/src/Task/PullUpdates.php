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
        echo shell_exec("pwd");

        // shell_exec("git pull");
        // echo "git pull <br />";
        // shell_exec("composer install");
        // echo "composer install <br />";
        // shell_exec("php perms.php");

        // exec("find . -type d -exec chmod 0775 {} +");
        // echo "Folders updated <br />\n";
        // exec("find . -type f -exec chmod 0664 {} +");
        // echo "Files updated <br />\n";
        // exec("chown -R www-data:www-data .");
        // echo "Ownership updated <br />\n";
    }
}
