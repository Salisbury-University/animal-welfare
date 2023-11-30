<h2> IMPORTANT NOTES</h2>
Hosting the website on Windows is not supported, However it may still work.

<br>
<h2>INFO ABOUT THE DEVELOPMENT ENVIRONMENT</h2>
The software was developed on Debian 11 (Bullseye) using PHP version 7.4. The database software we used is MariaDB (version 10.5.21) which is provided in the debian repositories.

https://www.debian.org/

<br>
<h2>SETTING UP THE WEBSITE</h1>
<ol> <!-- Begin website setup table -->
<li>Setup the server that will host the website</li><ol>
	<li>We paid for a virtual private server over at https://us.ovhcloud.com/ if you need a reliable hosting provider. You'll likely need to do some additional configuration to make it so that the login screen can only be accesssed by certain people if you do decide to go this route.</li>
	<li>For the operating system, I recommend Debian GNU/Linux (version 11, Bullseye). A newer version of Debian may also work (As of 10/31/2023, Some testing has begun on newer versions of php and debian, however if this has not been removed/updated then the newer version may have issues as the website was not fully tested/updated for the newer version)</li>
	</ol>

<li>Setup the database. The database can either be self-hosted or can be hosted by AWS or some other company.</li>
	<ol><li>You will need to import the .sql file "emptyZooDB.sql" that is located in the same folder as this README file.</li>
	</ol>

<li>Extract the files of the website to the directory that apache will serve web pages from </li><ol>
	<li> If you are using Debian, The location you want to extract the files to is located at "/var/www/html" </li>
	</ol>

<li> Edit the config file located at Config/masterConfig.ini </li>
	<ol><li> Fill out the information regarding the database located under the "[Database]" section</li>
	<li> If there is no user account already included in the empty database sql file, Go ahead and configure the recovery account located under the "[Recovery]" section. Please note that this account will be automatically disabled after you log in once and you will need to re-enable it if you wish to use it again. (This is done for security reasons) </li>
	</ol>

<li>Log into the website and click on Admin > Manage Admin, and then configure a new admin user.</li>

<li>And thats it!</li>
</ol> <!-- End website setup table -->

<br>
<h2> UPDATES </h2>
If any development happens after the semester ends, Updates will need to be manually done simply by deleting all the files for the website, downloading the website files again from the github, and then extracting the files to the directory where apache serves web pages.

<br>
<h2> BACKUPS </h2>
The software is thoroughly tested but since we are a small team theres always a chance that an issue could slip through the cracks. <br><br>
If you are using Debian or another Linux distribution, I recommend setting up an automatic database backup tool called "automysqlbackup". It provides daily, weekly, and monthly database backups and its also provided in the Debian software repository.
