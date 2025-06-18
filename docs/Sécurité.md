# Sécurité

## Argon2ID

Argon2ID est un algorithme de hachage de mots de passe conçu pour être résistant aux attaques par force brute et aux attaques par canal auxiliaire. Il utilise une combinaison de calcul intensif en mémoire et en temps pour rendre les attaques coûteuses en ressources. Sa capacité à être paramétrable permet de l'adapter aux besoins spécifiques de sécurité et aux ressources disponibles, offrant ainsi une protection robuste contre les attaques courantes sur les mots de passe.

Pour en savoir plus : [phc-winner-argon2/argon2-specs.pdf at master · P-H-C/phc-winner-argon2 · GitHub](https://github.com/P-H-C/phc-winner-argon2/blob/master/argon2-specs.pdf)

## Architecture par Token (Header Authorization lors des requêtes API)

Une architecture par token, utilisant le header Authorization dans les requêtes API, renforce la sécurité en exigeant un token d'authentification pour chaque requête. Cela permet de vérifier l'identité de l'utilisateur et de s'assurer qu'il a les droits nécessaires pour effectuer l'action demandée.

Le header Authorization est une méthode standard et sécurisée pour transmettre les tokens d'authentification. Il est moins vulnérable aux attaques par comparaison avec d'autres méthodes de transmission, comme les paramètres d'URL ou les cookies non sécurisés.

En exigeant un token dans le header Authorization pour chaque requête API, on s'assure que seules les requêtes authentifiées sont traitées. Cela protège contre les accès non autorisés.

De plus, les tokenssont générés avec une durée de vie limitée (15 jours par défaut) et peuvent être invalidés en cas de suspicion de compromission, permettant une gestion plus fine et plus sécurisée des sessions utilisateurs.

Nous stockons dans la base de données la date d'expiration du token afin de pouvoir nous assurer de garder un controle sur le token : cette approche est bien plus sécurisé que de juste indiquer dans le cookie de stockage du token cette date, et nous permet directement de pouvoir invalider un token via la base de données.

Son implémentation nous permet aussi de pouvoir limiter le nombre de fois où l'utilisateur doit taper son mot de passe, permettant une expérience utilisateur plus fluide

Pour en savoir plus : [Les jetons individuels de connexion ou token access | CNIL](https://www.cnil.fr/fr/les-jetons-individuels-de-connexion-ou-token-access)

## L’ancien mot de passe est demandé lors du changement de mot de passe (en plus du token)

Demander l’ancien mot de passe lors du changement de mot de passe ajoute une couche supplémentaire de vérification de l'identité. Cela protège contre les comptes compromis et empêche les changements de mot de passe non autorisés, même si un token est obtenu par des moyens malveillants. Cette mesure renforce la sécurité en s'assurant que seul le propriétaire légitime du compte peut effectuer des changements sensibles.

## Le mot de passe est demandé lors du changement de mail (en plus du token)

Exiger le mot de passe actuel lors du changement d'adresse e-mail vérifie l'identité de l'utilisateur et protège contre les attaques par prise de contrôle de compte. Cela empêche les changements de mail non autorisés, ce qui est crucial car l'adresse e-mail est souvent utilisée pour la récupération de compte et les notifications de sécurité. Cette mesure ajoute une couche de sécurité supplémentaire pour protéger les informations sensibles liées au compte.

## Architecture par conteneur (Docker)

Une architecture par conteneur offre plusieurs avantages en termes de sécurité. Les conteneurs isolent les applications et leurs dépendances, limitant l'impact des vulnérabilités et des attaques. Ils permettent également un déploiement cohérent et reproductible, réduisant les risques de configuration incorrecte. De plus, les conteneurs facilitent la gestion des dépendances, améliorent la scalabilité et la résilience, et permettent une réponse rapide aux vulnérabilités découvertes. Enfin, ils offrent une facilité de mise à jour et de correction, ce qui est essentiel pour maintenir un environnement sécurisé.

## Usage de Doctrine ORM

L'usage de Doctrine ORM renforce la sécurité de plusieurs manières. Tout d'abord, il protège contre les injections SQL en utilisant des requêtes paramétrées et un système de requêtes abstraites (DQL). Cela évite la concaténation directe de chaînes, qui peut mener à des vulnérabilités d'injection SQL.

Ensuite, Doctrine ORM fournit une couche d'abstraction entre l'application et la base de données. Cela permet de changer de base de données sans modifier le code de l'application, réduisant ainsi les risques liés à des configurations spécifiques à une base de données.

De plus, Doctrine ORM permet de définir des contraintes et des validations au niveau des entités. Cela aide à s'assurer que les données insérées ou mises à jour respectent les règles de validation définies, réduisant ainsi les risques d'injections de données invalides ou malveillantes.

Doctrine ORM simplifie également la gestion des relations entre les entités et des transactions. Cela permet de maintenir l'intégrité des données et de réduire les erreurs liées à la gestion manuelle des transactions, qui peuvent mener à des incohérences ou à des vulnérabilités.

En utilisant des méthodes comme `find()`, `findAll()`, et des requêtes DQL, Doctrine ORM génère des requêtes SQL sécurisées, réduisant ainsi le risque d'erreurs humaines dans la construction des requêtes.


