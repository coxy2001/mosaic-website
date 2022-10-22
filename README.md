## Overview

Mosaic All Investment Calculator installer

---

## Installation

Clone the repo
`git clone https://github.com/coxy2001/mosaic-website.git ~mosaic`

Install dependencies  
`composer install`

Create silverstripe-cache directory  
`mkdir silverstripe-cache`

Copy dev.env and rename to .env  
`cp dev.env .env`  
Edit values to match your webserver and sql config

Edit RewiteBase on line 32 public/.htaccess

Create mosaic_cms table in your sql database

Open the website in a browser, add /dev/build to the end of the base url  
eg: http://localhost/~mosaic/dev/build  
This will build the database and cache classes and functions.  
Run /dev/build everytime you add or remove classes or functions

See [Getting Started](https://docs.silverstripe.org/en/4/getting_started/) for more information.

---

## Site Setup

Open the website in a browser, add /admin to the end of the base url  
eg: http://localhost/~mosaic/admin

Goto Pages  
Change the homepage pagetype to TickerPage

Goto Settings  
Change the site name, logos, and disclaimer

---

## Cron Setup

Edit the server cron definition  
`sudo nano /etc/cron.d/silverstripe-crontask`

Run every minute, Silverstripe will handle scheduling  
`* * * * * www-data /usr/bin/php /PATH_TO_SILVERSTRIPE_DOCROOT/vendor/bin/sake dev/cron`

See [Silverstripe CronTask](https://github.com/silverstripe/silverstripe-crontask) for more information.
