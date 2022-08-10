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
        $this->executeAndPrint("git pull");
        echo "<h3>Running: composer install</h3>";
        $this->executeAndPrint("composer install");

        echo "<h3>Running: perms</h3>";
        $this->executeAndPrint("find . -type d -exec chmod 0775 {} +");
        echo "Folders updated <br>";
        $this->executeAndPrint("find . -type f -exec chmod 0664 {} +");
        echo "Files updated <br>";
        $this->executeAndPrint("chown -R www-data:www-data .");
        echo "Ownership updated <br>";
    }

    private function executeAndPrint(string $cmd)
    {
        $out = shell_exec("cd .. && " . $cmd);
        echo str_replace("\n", "<br>", $out);
    }
}
