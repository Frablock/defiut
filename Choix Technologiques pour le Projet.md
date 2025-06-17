# Choix Technologiques pour le Projet

## React pour l'interface

**Pourquoi React ?**

1. **Composants réutilisables** : React permet de créer des composants réutilisables, ce qui facilite la maintenance et l'évolution du code.
2. **Performance** : Grâce à son système de Virtual DOM, React offre des performances optimales pour les applications dynamiques.
3. **Communauté et écosystème** : React bénéficie d'une grande communauté et d'un écosystème riche, ce qui facilite la résolution des problèmes et l'intégration de nouvelles fonctionnalités.
4. **Flexibilité** : React peut être utilisé avec d'autres bibliothèques ou frameworks, ce qui le rend très flexible.

## Symfony (et PHP) pour le BackEnd

**Pourquoi Symfony et PHP ?**

1. **Stabilité et maturité** : Symfony est un framework mature et bien établi, avec une large communauté et une documentation complète.
2. **Modularité** : Symfony est basé sur des composants indépendants, ce qui permet de n'utiliser que ce dont on a besoin.
3. **Sécurité** : Symfony offre de nombreuses fonctionnalités de sécurité intégrées, telles que la protection contre les attaques CSRF et XSS.
4. **Performance** : PHP est un langage largement utilisé et optimisé pour le développement web, et Symfony est conçu pour être performant.

## Docker pour faciliter le déploiement

**Pourquoi Docker ?**

1. **Consistance de l'environnement** : Docker permet de créer des environnements de développement, de test et de production identiques, ce qui réduit les problèmes liés à la configuration.
2. **Isolation** : Les conteneurs Docker isolent les applications et leurs dépendances, ce qui améliore la sécurité et la stabilité.
3. **Portabilité** : Les conteneurs Docker peuvent être exécutés sur n'importe quelle machine équipée de Docker, ce qui facilite le déploiement et la scalabilité.
4. **Gestion des dépendances** : Docker simplifie la gestion des dépendances en les incluant dans le conteneur, ce qui évite les conflits de versions.

## Python pour faire les tests (via request)

**Pourquoi Python pour les tests ?**

1. **Simplicité et lisibilité** : Python est connu pour sa syntaxe claire et concise, ce qui rend les tests faciles à lire et à écrire.
2. **Bibliothèques de test** : Python offre des bibliothèques puissantes pour les tests, comme `requests` pour les tests d'API.
3. **Automatisation** : Python est idéal pour automatiser les tests, ce qui est essentiel pour garantir la qualité du code et détecter rapidement les régressions.
4. **Intégration** : Python peut facilement s'intégrer avec d'autres outils et systèmes, ce qui facilite l'intégration des tests dans le pipeline de développement.

## MySQL pour la base de données

**Pourquoi MySQL ?**

1. **Performance et fiabilité** : MySQL est connu pour sa performance et sa fiabilité, ce qui en fait un choix populaire pour les applications web.
2. **Écosystème et support** : MySQL bénéficie d'un large écosystème et d'un support communautaire et commercial solide.
3. **Compatibilité** : MySQL est compatible avec de nombreuses applications et frameworks, ce qui facilite son intégration dans le projet.
4. **Fonctionnalités avancées** : MySQL offre des fonctionnalités avancées telles que la réplication, le partitionnement et la prise en charge des transactions ACID.

## PHPMyAdmin comme utilitaire de gestion de données

**Pourquoi PHPMyAdmin ?**

1. **Interface utilisateur intuitive** : PHPMyAdmin offre une interface graphique conviviale pour gérer les bases de données MySQL, ce qui simplifie les tâches d'administration.
2. **Fonctionnalités complètes** : PHPMyAdmin permet d'effectuer diverses opérations sur les bases de données, telles que la création et la modification de tables, l'exécution de requêtes SQL, et la gestion des utilisateurs.
3. **Accessibilité** : Étant une application web, PHPMyAdmin peut être accessible depuis n'importe quel navigateur, ce qui facilite son utilisation.
4. **Intégration** : PHPMyAdmin s'intègre facilement avec MySQL et peut être déployé rapidement, ce qui en fait un outil pratique pour les développeurs et les administrateurs de bases de données.

## Argon2ID pour le Hashage et salage des mots de passe

**Pourquoi Argon2ID ?**

1. **Sécurité** : Argon2ID est conçu pour être résistant aux attaques par force brute et aux attaques par canal auxiliaire. Il a remporté le concours de hachage de mots de passe en 2015.
2. **Flexibilité** : Argon2ID permet de configurer des paramètres tels que le temps de calcul, la mémoire utilisée et le nombre de threads, ce qui permet d'adapter la sécurité aux besoins spécifiques du projet.
3. **Support communautaire** : Argon2ID est largement reconnu et soutenu par la communauté de la sécurité, ce qui garantit sa fiabilité et sa pérennité.
4. **Compatibilité** : Argon2ID est compatible avec de nombreuses bibliothèques et frameworks, ce qui facilite son intégration dans le projet.

## Conclusion

Chaque choix technologique a été fait en tenant compte des besoins spécifiques du projet, de la performance, de la sécurité, de la maintenabilité et de l'évolutivité. En combinant ces technologies, nous visons à créer une application robuste, sécurisée et facile à maintenir.
