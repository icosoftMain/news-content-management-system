

*Installation process on the localhost*

- Make sure to set the username and password of your sql server for this software in order to do so: 

- Go to the app folder.

- Click the file named '.app_reg'.

- Set the default field of the [SERVER_CONFIG_USERS] to your registered username of your sql server.

- Set the default field of the [SERVER_CONFIG_PASSWORDS] to your registered password of your sql server.

**Then**

- Put the project in your htdocs.

- Turn on your sql and apache server.

- Open the software files on your favorite text editor or ide.

- Open your terminal or command prompt.

- Change the director to this software's folder.

- Then enter the command 'php fly-env migrate_models'.

- Now you can run the software. 


*Installation process on the web sever*

**To locate the sql or database files**

- Go to the app folder.

- Go to models.

- The database files are models.sql and handlers.sql.


**To change the username and password for the sql server**

- Go to the app folder

- Click the file named '.app_reg'

- Set the default field of the [SERVER_CONFIG_USERS] to registered username.

- Set the default field of the [SERVER_CONFIG_PASSWORDS] to registered password.

- However if you would want to change the database name, set the default field of the
[APP_MODELS] to the database you have created for this software.

- Before you run this software you would have to run the queries of the database files 'models.sql' and 'handlers.sql' accordingly.


**The last to do**

- Click on the file 'watch.json'.

- Look for the field called 'ssl'.

- Set the ssl  field to 'true'.

- Now you can run the software.


**Admin Login Credentials.**

-------------------------------------
| Username       | Password         |
-------------------------------------
| @ilapiking     | ilapiking123     |
-------------------------------------








