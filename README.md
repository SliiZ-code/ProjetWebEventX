# ProjetWebEventX

sudo docker-compose down -v
sudo docker-compose build
sudo docker-compose up -d

1. Démarrer les services
docker-compose up -d

2. Voir les logs
docker-compose logs -f

3. Arrêter les services
docker-compose down

4. Supprimer tout (conteneurs + volumes)
docker-compose down -v


5. Visualiser la bd 
sudo docker exec -it eventx_db mysql -uuser -ppassword demo
show tables;

6. Déboguer l'erreur JSON
# Vérifier que l'API répond correctement
curl http://localhost:8000/api/event

# Voir les logs de l'API
docker-compose logs api

# Tester la réponse dans le navigateur
# Ouvrir les outils de développement (F12) -> Network tab
# Vérifier le Content-Type des réponses (doit être application/json)

Interface client : http://localhost:3000
API événements : http://localhost:8000/api/event
Page événements : http://localhost:3000/events.html

log in = connexion
register = inscription

Les polyfills Symfony sont des dépendances obligatoires de Twig. 

jean.dupont@eventx.com 
password
$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

Vendor :
sudo docker exec -it eventx_client composer install
