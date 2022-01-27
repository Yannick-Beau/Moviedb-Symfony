# Services

- Il existe dans Symfony FW un "conteneur de services" (service container).
- Dans ce conteneur sont "référencés" des "services"
  - Ces services sont des classes qui pourront être instanciées pour fournir l'objet demandé à notre programme.
  - Exemples de services
- On peut récupérer ces services dans les méthodes des contrôleurs ou dans les constructeurs des services eux-mêmes.
  - par "injection de dépendance"
- `bin/console debug:autowiring`
  - => permet de connaitre les listes des services "injectables"
- On peut réutiliser ces services d'à peu près n'importe où dans notre code source.