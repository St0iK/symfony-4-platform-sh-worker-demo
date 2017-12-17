Symfony 4 Demo Application on Platform.sh setup with worker
========================

* [Redis on platform.sh](https://docs.platform.sh/configuration/services/redis.html)
* [Platform.sh workers](https://docs.platform.sh/configuration/app/workers.html#workers)
* [Enqueue Bundle](https://github.com/php-enqueue/enqueue-bundle)
* [Redis transport](https://github.com/php-enqueue/enqueue-dev/blob/master/docs/transport/redis.md)



Platform.sh Environment variables
------------

You can use the `env` namespace to sent environment variables
![platform.sh env variables](https://i.imgur.com/9sCR7GM.png)

Example
------------
Visit https://master-7rqtwti-ch5zunof22dqu.eu.platform.sh/en/blog/add to send a message to the queue.

The consumer running on the worker service will pick it up and create a new test blog post 
https://master-7rqtwti-ch5zunof22dqu.eu.platform.sh/en/blog/

Worker configuration
------------
```
workers:
    queue:
        commands:
            start: |
                ./bin/console enqueue:consume -e=prod
        disk: 256
        size: 'M'
        mounts:
            "/var/cache": "shared:files/cache"
            "/var/log": "shared:files/log"
            "/var/sessions": "shared:files/sessions"
        relationships:
            database: "mysql:mysql"
            applicationcache: "rediscache:redis"
        variables:
            env:
                type: 'worker'
```

SSH into worker's environment
------------

You can SSH into the environment that was created for the worker

`{platform-site-id}-{environment}@ssh.eu.platform.sh`

ex. if your worker's environment name is `playground--queue`

`tqskajshauw-master-7jashsya--playground--queue@ssh.eu.platform.sh`

