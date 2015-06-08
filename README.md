# CUSTOM CAKEPHP 3.X

## Ajuste dos campos de data para o formato SQL americano.

### Como usar

Add no Model:

```php
<?php

    class PostsTable extends Table 
    {
        public function initialize($config = [])
        {
            $this->addBehavior("JCustomCakephp3.ConvertDate");
        }
    }
```
ou
```php
<?php

    class PostsTable extends Table 
    {
        public function initialize($config = [])
        {
            $this->addBehavior("JCustomCakephp3.ConvertDate", ['fild_name1', 'fild_name2']);
        }
    }
```