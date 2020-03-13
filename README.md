# CRUD-B User Manager
### Instalation
``composer config repositories.crudb git https://github.com/ersaazis/usermanagement.git``

``composer require ersaazis/usermanagement``

``php .\artisan vendor:publish --tag=usermanagement``

Update file ``database/seeds/DatabaseSeeder.php``
tambahkan 
``$this->call(UserManagementMigrationSeeder::class);``
di function run()

``php .\artisan db:seed ``
