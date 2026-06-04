## About QRTree

This is only back-end for QRTree.link website

Laravel ver. 12
Filament ver. 5

## Install BackEnd

- copy to new file '.env.exmple' to '.env' and update database information
- after clone project you have to run `composer install` in terminal
- Run `php artisan migrate`
- Run `php artisan db:seed` in terminal
- or run `php artisan migrate:fresh --seed` in terminal
- run `php artisan key:generate` in terminal
- Run `php artisan storage:link` in terminal

## Note:

- check AdminTableSeeder to know login information for super user
- this help you to run migrtion & seeder `php artisan migrate:fresh --seed`
- this system use Vite, if you made any change in front-end, you have to run `npm run build` in terminal

## laravel-translations

this repo use [laravel-translations](https://github.com/MohmmedAshraf/laravel-translations) package

- make sure to run `php artisan translations:import`
- To access the translations UI, visit /translations in your browser.
- If you are using a production environment, you will need to create owner user first. To do so, run the following command: `php artisan translations:contributor`

## BackEnd Support

If you need any help please contact us +201005222120 or [alfker3@gmail.com](mailto:alfker3@gmail.com).
