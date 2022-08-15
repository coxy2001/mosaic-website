## Overview

Mosaic All Investment Calculator installer

---

## Installation

Clone the repo to a directory in your webserver  
`git clone https://github.com/coxy2001/mosaic-website.git ~mosaic`

Install dependencies  
`composer install`

Create silverstripe-cache directory  
`mkdir silverstripe-cache`

Copy dev.env and rename to .env  
`cp dev.env .env`  
Edit SS_BASE_URL, SS_DATABASE_USERNAME, SS_DATABASE_PASSWORD in .env to match your webserver and sql config

Create mosaic_cms table in your sql database

Open the website in a browser, add /dev/build to the end of the base url  
eg: http://localhost/~mosaic/dev/build  
This will build the database and cache classes and functions.  
Run /dev/build everytime you add or remove classes or functions

See [Getting Started](https://docs.silverstripe.org/en/4/getting_started/) for more information.
