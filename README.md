# laravel-promotions

Install package and execute migrations and seeds

php artisan genetsis-admin:install-promotions


// if use templates

change config filesystems public path

'url' => env('APP_URL').'/assets/public',

ln -s /var/www/storage/app/public /var/www/html/assets


### For GTM Events:

Add to EventServiceProvider the GTM events

    protected $subscribe = [
        GTMPromoSubscriber::class
    ];
    
### For EMails:

Add to EventServiceProvider listener

    'promouser.participated' => [
        UserParticipated::class
    ],
    
