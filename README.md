# Installation Instructions

Please open an issue if you can't do any of these steps :

- Download phpBB version 3.1.10 [here](https://www.phpbb.com/files/release/phpBB-3.1.10.zip)
- Create directory **{Wamp}/www/{trucfou}/forum/** (for a windows WAMP setup). It's already included in gitignore so don't worry about it.
- Unzip **phpBB-3.1.10.zip** somewhere. A **phpBB3** directory is created. Copy all of its content in your webserver **forum** folder. You can delete the zip file and the phpBB3 folder, they won't be useful.
- Start Wamp if it's not already started.
- Go to **{localhost}/phpmyadmin** and manually create a database. Then go to the *import* tab and open **trucfou.sql**, then import tables. This create the necessary tables for the trucfou project :)
- Go to **{localhost}/trucfou/forum/install/** and follow the instructions. Choose *localhost* as host, the name of the database you created before, leave the port empty, and choose *root* as database user and an empty password. Choose an fake admin name and password for the forum admin account. The setup will then automatically create all databases tables necessary for phpBB3 !
- Delete the **{Wamp}/www/{trucfou}/forum/install** folder
- Create the file **{Wamp}/www/{trucfou}/include/config.php**, containing this :
'''
<?php
try {
	$bdd = new PDO( 'mysql:host=localhost;dbname=YOUR_DB_NAME;charset=utf8', 'root', '');
}

catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
} ?>
'''

You're officialy ready to go !