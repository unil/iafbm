Composants
==========

L'application **iafbm** comporte des composants qui lui sont propres.


Sécurité
--------

La sécurité est **orientée ressources**. Pour chaque rôle sont définies quelles sont les opérations CRUD autorisée sur les différentes ressources.


Authentification
----------------

L'authentification dans l'application iafbm se fait par un connecteur de type `shibboleth <http://fr.wikipedia.org/wiki/Shibboleth>`_ qui récupère les données d'authentification fournies par le système `Switch-AAI <http://www.switch.ch/fr/aai/>`_.

Les données d'authentification récupérées sont:

* **Identité**
    * PERSON_UID
    * SWISSEP_HOMEORGANIZATION
* **Rôle**
    * CUSTOM_UNILMEMBEROF


Versioning
----------

Le versioning des données est fait au **niveau logiciel**. La logique implémentée est de **type différentielle**: pour chaque modification, le système conserve le **delta** modifié.
Les versions antérieures peuvent alors être recomposées en appliquant les deltas successifs sur la version à jour stockée.

Le système de versioning permet aussi la création de **tags** utilisateur associés à un label. Ceci permet de créer jalons nominatifs pour une version donnée d'une ressource.

Implémentation
``````````````

Le versioning est implémenté dans la classe `iaModelMysql <http://unil.github.com/iafbm/documentation/api/server/classes/iaModelMysql.html>`_ (`code <https://github.com/unil/iafbm/blob/master/iafbm/lib/iafbm/xfm/iaModelMysql.php>`_).

* La lecture d'un enregistrement versioné déclenche la `logique de fusion des données différentielles <https://github.com/unil/iafbm/blob/master/iafbm/lib/iafbm/xfm/iaModelMysql.php#L134>`_:
    #. L'enregistrement actuel est chargé depuis la base de données
    #. Les différences de chaque version sont appliquées dans l'ordre déchronologique, jusqu'à la version demandée

* L'ajout ou la modification d'un enregistrement déclenche la `logique de stockage différentiel <https://github.com/unil/iafbm/blob/master/iafbm/lib/iafbm/xfm/iaModelMysql.php#L218>`_ des données modifiée:
    #. L'enregistrement est ajouté ou modifié dans la base de données
    #. Les différences sont enregistrées dans une version

* La suppression d'un enregistrement déclenche la logique de `suppression douce <https://github.com/unil/iafbm/blob/master/iafbm/lib/iafbm/xfm/iaModelMysql.php#L277>`_:
    #. Le système `vérifie <https://github.com/unil/iafbm/blob/master/iafbm/lib/iafbm/xfm/iaModelMysql.php#L302>`_ que la suppression est valide
    #. Le champs **actif** de l'enregistrement est mis à 0
    #. La différence est enregistrée dans une version



Archivage
---------

L'archivage des données fonctionne par applatissement des structures de données. Lorsqu'une ressource est archivée, tous les champs qu'elle contient ainsi que les champs des ressources associées sont stockée de manière plate dans une table de la base de données.


Recherche
---------

Recherche par attribut
``````````````````````

Recherche full-text
```````````````````