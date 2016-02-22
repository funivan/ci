# Copy db

# Clone repository
# Configure `ci.app.php`

# Configure cron
```
* * * * * php artisan schedule:run >> /dev/null 2>&1
```

# How check commit
- invoke http://127.0.0.1:8080/add-build?commit=0270966ad4a47e73c1ffcd28f5895b74da1b205f&branch=master&committer=dev@funivan.com