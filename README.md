# About WEB10

Web10 is a website management system built for many, smaller websites.  It is meant for website makers, developers, website companies or anyone else looking to setup multiple websites.  The system allows site administrators as well as clients edit websites.

# Installation - Requirements
* Install PHP, Install MySQL (can use a LAMP stack like XAMPP for Windows for MAMP for Mac)
* Make sure Doctrine >= 2.1 (http://www.doctrine-project.org/) is on your php include_path (you can install this using pear or simply drop the Doctrine directory into the <application directory>/lib folder)
* Make sure Twig is on your include path (http://twig.sensiolabs.org/)
* Make sure QueryPath is on your include path (http://querypath.org/)

# Installation - Automatic Method
* Open deploy.php and edit the options at the top
  * HID: This is the name used for the database name and the database username
  * HOSTS: Space delimited list of hostnames to be mapped to the first website to be installed
* Run 'php deploy.php web10'
* In your browser, go to http://localhost/

# Installation - Manual Method
* Add a database named web10 to MySQL
* Execute the following in MySQL: grant all on web10.* to web10@localhost;
* In apache, set the document root to be "<application directory>/wwwroot/"
* Go into <application directory>/lib/Web10/Common/McKinleyClassLoader.php and change the define statement at the top for ROOTPATH to be the full path to the application directory
* Go into <application directory>/lib/Web10/Common/CoreContainer.php and in the function setupCore change the path for rootpath to the path to your application directory
* Make sure your apache can run .htaccess files and edit
* In the .htaccess file, adjust the include_path to include <application directory>/lib
* Run 'php setup.php localhost' on the command prompt
* In your browser goto http://localhost/
