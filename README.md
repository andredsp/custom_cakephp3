# CUSTOM CAKEPHP 3.X

## Instalação

```php
<?php
    Plugin::load("JCustomCakephp3");
```

## Como usar

### 1. Ajuste dos campos de data para o formato SQL americano.

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

### 2. Validação de dados brasileiros.

Add no Model no método validationDefault():

```php
<?php

public function validationDefault(Validator $validator)
{
    $validator
        ->provider('custom', new \JCustomCakephp3\Validation\CustomProvider)
        ->add('birth', 'valid', ['rule' => 'dateBR', 'provider' => 'custom'])
        ->requirePresence('birth', 'create')
        ->notEmpty('birth');
}
```

Rules:
- dateBR
- datetimeBR
- cnpj
- cpf
- cep
- phone
- cellphone