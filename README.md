# realcruz
## Local Setup

- Clone the repo to your local directory
- copy .env.example to .env by ```cp .env.example .env```
- Install php and mysql dependencies  
- execute the command ```php artisan serve ``` inside the project diectory.
- open the link provided in the terminal, install if any dependencies suggested and give appropirate permissions to files as suggested
- go to next tab, file all the details 
- go to next tab, here we need to give the database connection details
- create a mysql user and database and provide the details in  ```.env``` file.
- fill the details in installation steps also, as we are using local setup.so,we need to comment a line in code
  - comment the line 223 in file ```/realcrux/app/Http/Controllers/InstallController.php ```
  - Then click on save on the installation process
  - Now uncomment the line after going to next step
- now click on next to data migration, it will take some time to migrate the data
- After that it will redirect to the finish page.

> for logs visit ```storage/logs/laravel.log```
> Update ```.gitignore``` file if anything is not required in repo
