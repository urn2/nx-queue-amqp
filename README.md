# nx-queue-amqp
amqp queue for nx


> composer require urn2/nx-queue-amqp

```
queue/amqp'=>[
    'default'=>[
        'conn'=>['127.0.0.1', 56720, 'guest', 'guest'],
        'route'=>[
            'rabbitmq_exchange_queue_route'=>[
                'exchange'=>[
                    'name'=>"rabbitmq_exchange",
                    'type'=>'direct',
                ],
                'queue'=>[
                    'name'=>'rabbitmq_exchange_queue',
                ],
                'declare'=>false,
            ],
        ],
    ],
]
```