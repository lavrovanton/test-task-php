# test-task-go
## Deploy
```
docker compose up -d

# go to app container
docker compose exec -it php bash
composer install
chown www-data:www-data -R .

yii migrate
yii data/create-admin
yii data/generate-fake
```

## Site
http://localhost:8000

Username: admin\
Password: admin
