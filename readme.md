# Installation
- `git clone https://github.com/funivan/ci.git`
- `cd ci`
- `composer install`
- `touch database/database.sqlite`
- `php artisan migrate:refresh`
- edit configuration file `ci.app.php`
- add to cron ` * * * * * php artisan schedule:run >> /dev/null 2>&1`
- clone your repository to `build` directory. You can change location inside `ci.app.php`

# Manual usage
- add commit to the queue `php artisan ci:add-commit master 0270966ad4a47e73c1ffcd28f5895b74da1b205f dev@funivan.com`
- check commit `php artisan ci:check 0270966ad4a47e73c1ffcd28f5895b74da1b205f`

# How check commit automatically?
 Create git hook `post-receive`
```sh
#!/bin/sh
#
# An example hook script for the "post-receive" event.
#
# The "post-receive" script is run after receive-pack has accepted a pack
# and the repository has been updated.  It is passed arguments in through
# stdin in the form
#  <oldrev> <newrev> <refname>
# For example:
#  aa453216d1b3e49e7f6f98441fa56946ddcd6a20 68f7abf4e6f922807889f52bc043ecd31b79f814 refs/heads/master
#

SERVER_URL="ci.yourserver.com";

trigger_hook() {
        NEWREV="$2"
        REFNAME="$3"

        if [ "$NEWREV" = "0000000000000000000000000000000000000000" ]; then
                # Ignore deletion
                return
        fi

        case "$REFNAME" in
                # Triggers only on branches and tags
                refs/heads/*|refs/tags/*) ;;
                # Bail out on other references
                *) return ;;
        esac

        BRANCH=$(git rev-parse --symbolic --abbrev-ref "$REFNAME")
        COMMITTER=$(git log -1 "$NEWREV" --pretty=format:%ce)
        MESSAGE=$(git log -1 "$NEWREV" --pretty=format:%s)

        echo "Sending webhook"
        curl "http://$SERVER_URL/add-build?hash=$NEWREV&author=$COMMITTER&branch=$BRANCH&message=$MESSAGE"
}

if [ -n "$1" -a -n "$2" -a -n "$3" ]; then
  PAGER= trigger_hook $1 $2 $3
else
  while read oldrev newrev refname; do
    trigger_hook $oldrev $newrev $refname
  done
fi

```

# For developers
- clone this repository
- run `touch database/database.sqlite`
- create `.env` file with the following code `APP_DEBUG = true`
- run `php artisan migrate:refresh`
- start server `php -S 127.0.0.1:8080 server.php`
- open `http://127.0.0.1:8080/`
