# NotenDB #

Erstellt von Max Weller und Moritz Willig im Rahmen einer besonderen
Lernleistung an der Werner-von-Siemens-Schule Wetzlar.


## Install ################################################

1. Create a database
2. Execute `create_tables.sql` on this database to create structure
3. Rename `.htconfig.php_template` to `.htconfig.php`
4. Edit configuration parameters in `.htconfig.php`
5. Create db.ini according to template found in `.htconfig.php`

## Create admin account ###################################

1. You need to enable the installation routine by changing the following
   line in `controller/install.php`:
   ```php
   /**
    * should be ALWAYS disabled for security reasons!!!
    */
   var $ENABLED = false;
   ```
   to
   ```php
   var $ENABLED = true;
   ```

2. After that, navigate the following URL in your browser:
   ```
   http://<your-domain>/<your-path>/install/create_admin
   ```
   This creates the user `ADMIN` with password `54tzck23`.

3. Finally, disable the installation routine again by reverting the change from step 1.

