<?php
exec("find . -type d -exec chmod 0775 {} +");
echo "Folders updated <br />\n";
exec("find . -type f -exec chmod 0664 {} +");
echo "Files updated <br />\n";
exec("chown -R www-data:www-data .");
echo "Ownership updated <br />\n";
