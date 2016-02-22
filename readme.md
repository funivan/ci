# Installation
- `git clone https://funivan@bitbucket.org/funivan/simple-ci.git`
- `cd simple-ci`
- `composer install`
- `php artisan migrate:refresh`
- edit configuration file `ci.app.php`
- add to cron ` * * * * * php artisan schedule:run >> /dev/null 2>&1`
- clone your repository to `build` directory. You can change location inside `ci.app.php`

# Manual usage
- add commit to the queue `php artisan ci:add-commit master 0270966ad4a47e73c1ffcd28f5895b74da1b205f dev@funivan.com`
- check commit `php artisan ci:check 0270966ad4a47e73c1ffcd28f5895b74da1b205f`

# How check commit automatically?
 Call url on `post-receive` `http://127.0.0.1:8080/add-build?hash=0270966ad4a47e73c1ffcd28f5895b74da1b205f&branch=master&author=dev@funivan.com`