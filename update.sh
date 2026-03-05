# Copyright (c) 2023. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
# @author cyndelherolt
# @project UniFolio

#!/usr/bin/env bash

touch maintenance.lock
echo "Début mise à jour"
echo "Git Pull"
git pull origin main
echo "end git pull"

echo "generation des assets"
npm run build
echo "fin génératation des assets"

echo "Nettoyage cache"
bin/console cache:clear
echo "end Nettoyage cache"
echo "Mise à jour les liens js"
bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json
echo "end Mise à jour les liens js"
echo "Optimisation Composer"
composer dump-autoload --no-dev --classmap-authoritative
echo "end Optimisation Composer"
chmod -R 775 var/
chown -R root:www-data var/
echo "Relance des workers"
bin/console messenger:stop-workers
echo "Fin relance des workers"
echo "Fin mise à jour"
rm maintenance.lock