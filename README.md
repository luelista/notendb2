# NotenDB #

Erstellt von Max Weller und Moritz Willig im Rahmen einer besonderen
Lernleistung an der Werner-von-Siemens-Schule Wetzlar.


-----

Es folgt ein Auszug aus der Dokumentation zu Systemanforderungen und Installation. Die vollständige Dokumentation finden Sie unter http://notendb-hilfe.wikilab.de

-----


## 6. Praxis ##
In diesem Kapitel fassen wir einige Hinweise und Anleitungen zur Installation, Wartung, Verwendung und Weiterentwicklung unserer Software zusammen. Ausführlichere Schritt-für-Schritt-Anleitungen und Handbücher sind dagegen im Anhang zu finden.

### 6.1 Systemvoraussetzungen ###
Clientseitig wird zur Noteneingabe nur ein Webbrowser (Firefox, Chrome, Safari) benötigt.
Um Zeugnisse zu drucken ist zusätzlich entweder Microsoft Office oder ein PDF-Betrachter (z.B. Adobe Reader) erforderlich.

Serverseitig kommt ein Linux-System zum Einsatz, auf dem für die Grundfunktionen ein LAMP-Stack (weit verbreitete Webserver-Einrichtung bestehend aus Apache, MySQL und PHP) eingerichtet sein muss.

Ein LAMP-Stack kann unter Ubuntu sehr einfach mit folgenden Befehlen eingerichtet werden:
```
$ sudo apt-get install tasksel
$ sudo tasksel install lamp-server
```

Danach sind eventuell Anpassungen der Konfiguration nötig, die hier nicht beschrieben werden können. Hierzu sind jedoch ausführliche Anleitungen im Internet zu finden. 

Für verschiedene Zusatzfunktionen sind folgende weitere Pakete nötig:

 *  Für die Erstellung von Microsoft Excel- Arbeitsblättern muss das Java Runtime Environment installiert sein.
 *  Für die Erstellung von PDF-Dateien muss die TeX-Distribution TeXLive muss installiert sein. 
    Die Installation wird für Ubuntu und OpenSUSE wie folgt durchgeführt:
    
    Ubuntu:    
    ```
    # apt-get install texlive
    ```
    
    OpenSUSE:    
    ```
    # zypper install texlive texlive-latex
    ```

### 6.2 Installation ###
Wenn die oben angegebenen Systemanforderungen erfüllt sind, ist die Installation in wenigen Schritten zu erledigen.

  1.  Kopieren Sie die Anwendung (Ordner notendb2) in ein Verzeichnis, welches über den Webserver erreichbar ist (DocumentRoot).

      Wir empfehlen, die Anwendung direkt aus dem Git-Repository herunterzuladen, um die neueste Version zu erhalten. Diese ist mit folgendem Befehl möglich:

      ```
      $ git clone https://github.com/max-weller/notendb2.git
      ```

  2.  Rufen Sie das Hauptverzeichnis der Anwendung über einen Webbrowser auf. 
      Sie werden beim ersten Aufruf automatisch auf den Installationsassistenten
      weitergeleitet (./install.php).

  3.  Folgen Sie den Anweisungen des Installationsassistenten.
      Sie sollten insbesondere, falls noch nicht geschehen, eine eigene MySQL-Datenbank für die 
      Installation der notendb2 auf dem MySQL-Server einrichten.

  4.  Nachdem ein Schritt erfolgreich abgeschlossen wurde, wird das graue Kreuz in der Überschrift 
      jeweils durch ein grünes Häkchen ersetzt.

      Wenn alle Schritte abgeschlossen sind, klicken Sie auf “Konfiguration schreiben” und “Beenden”.
         

Mit Klick auf “Zur Startseite” gelangen Sie dann zum Loginformular, in dem Sie sich mit dem Benutzernamen “root” und dem eben vergebenen Passwort erstmalig einloggen können.
 
Hinweis: Sie können die während der Installation angegebenen Parameter jederzeit verändern, indem Sie den Installations-assistenten erneut aufrufen. Hängen Sie dazu an die URL zum Hauptverzeichnis der Anwendung ``install.php` an. Sie benötigen zum erneuten Starten das Passwort des Superadministrators root, welches Sie bei der Installation vergeben haben.

Sollten Sie dieses Passwort nicht mehr wissen, können Sie in der Konfigurationsdatei ``.htconfig.php`` nachsehen. Es befindet sich in der Zeile, die mit ``$SITE_CONFIG[“LOGIN_ROOTPASW”]`` beginnt.


------------


## notendb2 LICENSE ###############################

Copyright © 2012-2013 Max Weller, Moritz Willig

![Creative Commons Lizenzvertrag](http://i.creativecommons.org/l/by-nc-nd/3.0/88x31.png)

notendb2 von [Max Weller und Moritz Willig](http://notendb-hilfe.wikilab.de) steht unter
einer [Creative Commons Namensnennung-NichtKommerziell-KeineBearbeitung 3.0 Unported Lizenz](http://creativecommons.org/licenses/by-nc-nd/3.0/deed.de).

Weitere Informationen, eine stets aktuelle Version
dieses Dokuments sowie den Quelltext der Software
finden Sie auch unter http://notendb-hilfe.wikilab.de/


