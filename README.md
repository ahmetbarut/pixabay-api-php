## Pixabay Api PHP 
[Pixabay](pixabay.com) için php api.

### Kurulum
```bas
    composer require ahmetbarut/pixabay:dev-master
```


### Basit Kullanım
```php
    require_once "./vendor/autoload.php";

    use Pixabay\Client;

    $pixabay = new Client([
        "key" => "api_key",
        "lang" => "tr",
        "image_type" => "photo",
        "q" => "kediler"
    ]);

    $response = $pixabay->body();

    print_r($response->hits);
```

#### [Dökümantasyon](https://github.com/ahmetbarut/pixabay-api-php/wiki)
