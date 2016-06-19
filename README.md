# cms-bundle

##Installing

just run
```bash
$ composer require dywee/core-bundle
```

add the bundle to the kernel
```php
new Dywee\CoreBundle\DyweeCoreBundle(),
```

Be sure to activate the symfony serializer component in your config.yml file

```yml
serializer:
    enabled: true
```
