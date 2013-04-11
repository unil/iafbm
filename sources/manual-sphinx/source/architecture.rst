Architecture
============

.. toctree::
   :maxdepth: 2


Généralités
-----------

L'application **iafbm** est une application de type client léger.


Briques logicielles
-------------------

L'application s'appuie sur les **briques logicielles principales** suivantes:

* `Socle PHP xfm <https://github.com/damiencorpataux/xfm-php>`_
* `Socle Javascript ExtJS <http://www.sencha.com/products/extjs/>`_

Les **briques logicielles secondaires** suivantes sont également utilisées:

* `DomPDF <http://code.google.com/p/dompdf/>`_
* `Minify <http://code.google.com/p/minify/>`_
* `ParseCSV <http://code.google.com/p/parsecsv-for-php/>`_
* `PHP Unit <http://www.phpunit.de/>`_

Systèmes d'information **connexes**:

* `Switch-AAI <http://www.switch.ch/fr/aai/>`_ (protocole `shibboleth <http://fr.wikipedia.org/wiki/Shibboleth>`_) pour l'authentification centralisée.


Typologie
---------

Partie serveur
``````````````
La partie serveur de l'application, implémentée à l'aide le socle logiciel `xfm <https://github.com/damiencorpataux/xfm-php>`_, respecte la forme architecturale dite `MVC <http://fr.wikipedia.org/wiki/Mod%C3%A8le-Vue-Contr%C3%B4leur>`_.

Partie client
`````````````
La partie client de l'application, implémentée à l'aide du socle logiciel `ExtJS <http://www.sencha.com/products/extjs/>`_, respecte la forme de conception orientée widgets (similaire à `Qt <http://fr.wikipedia.org/wiki/Qt>`_) et est basée sur le principe dit `signaux et slots <http://fr.wikipedia.org/wiki/Signaux_et_slots>`_.

Communication client-serveur
````````````````````````````
La communication client-serveur est implémentée selon le principe d'architecture `ReST <http://fr.wikipedia.org/wiki/Representational_State_Transfer>`_.
Les socles logiciels `xfm <https://github.com/damiencorpataux/xfm-php>`_ et `ExtJS <http://www.sencha.com/products/extjs/>`_ implémentent déjà ce principe demanière native.