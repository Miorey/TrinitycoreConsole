# PHP client for TrinityCore server
## Install
### Requirements
On Ubuntu the following libraries have to be installed:

```bash
sudo apt install -y php7.2 php7.2-xml php7.2-soap composer
```

Install dependencies by executing:
```bash
composer install
```

### Start server for dev
Setup your env vars:
Trinitycore console requires the following vars to be setup:

1. `TRINITY_USERNAME` account allowed to use trinitycore console
3. `TRINITY_PASSWORD` account password for the account
3. `TRINITY_ADDRESS` address of the worldserver

You can also setup the server by updating **service.yaml** file like follwing
```yaml
    App\TrinityCore\TrinityClient:
        arguments:
            $username: '%env(TRINITY_USERNAME)%'
            $password: '%env(TRINITY_PASSWORD)%'

        calls:
            - method: setServerAddress
              arguments:
                  - '%env(TRINITY_ADDRESS)%'
            - methode: setServerAddress
              arguments:
                  - '%env(TRINITY_PORT)%'
```
In 90% of the cases setup the port is useless because the default value is already set
to the default trinitycore value i.e. **7878**

symfony 4 is provided with a standalone server to start it execute:

```bash
php bin/console server:start
```

### Download and deploy the project

- Git
- Composer install

### First start

- .env file

## Docker