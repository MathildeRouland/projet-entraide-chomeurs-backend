# projet-06-entr-aide-chomeurs-back

## Pour installer notre projet :

Dans le terminal, aller à la racine du projet puis lancer la commande ``` composer install```
Créer un fichier .env.local et on ajoute :
```env
DATABASE_URL="mysql://UTILISATEUR:MDP@127.0.0.1:3306/NOMBDD?serverVersion=mariadb-10.3.25&charset=utf8mb4"
###> lexik/jwt-authentication-bundle ###
JWT_PASSPHRASE= CODE
###< lexik/jwt-authentication-bundle ###
``` 
Dans le DATABASE_URL remplacer UTILISATEUR par le véritable nom de l'utilisateur, MDP par le mot de passe et NOMBDD par le nom de la base de données. 
Et dans le JWT_PASSPHRASE remplacer CODE par la clé JWT.

Ensuite, dans le terminal créée la base de donnée :

```bash
php bin/console doctrine:database:create
```
Lancer les migrations : 
```bash
php bin/console doctrine:migrations:migrate
```
Et charger les fixtures :

```bash
bin/console doctrine:fixtures:load
```

Si vous souhaitez lancer le serveur :
```bash
php -S 0.0.0.0:8080 -t public
```

