<?php
// Konfiguration
  // Error-Reporting:
  // Einstellung für Setup/Entwicklung:
  error_reporting(E_ALL);
    // Einstellung für Produktionsumgebung/Betrieb:
  #error_reporting(NULL);  
  
  // Maximale Spanne der UNTÄTIGKEIT in Sekunden
  // 60=1min   300=5min 900=15min 1800=30min 3600=1h
  define ( 'SESSION_MAX_IDLE_TIME', 900 ); 
 
  // Das Verzeichnis für die Session-Dateien. Belässt man das originale kann es 
  // sein, dass PHP die Sessions zu früh "wegräumt":
  // Wenn das keine Software für Sie macht, dann müssen das Verzeichnis anlegen
  // und dafür sorgen, dass es beschreibbar ist.
  define ( 'SESSION_FILE_DIR', __DIR__ . '/sessions' );
 
  // Wenn Sie einen eigenen Server haben (kein Shared Hosting!) KANN es sinnvoll sein
  // diesen Wert auf false zu setzen, dann kann jeder Benutzer mit den Rechten der Gruppe
  // des Webservers (oft: www-data) die Session-Dateien ansehen und z.B. löschen.
  // Im Zweifelsfall auf true setzen.
  define('SHARED_HOSTING', true);
 
  // Speichermethode, hier Text/CSV 
  /* gilt für 
  * Passwort-Datei
  * Gruppen-Datei
  * LastLogin-Datei
  Gültige Einträge: csv 
  Geplante Einträge: json|database (Für künftige Versionen)
  */
  define ( 'STORAGE_METHOD', 'csv' ); 
  define ( 'STORAGE_DIR', __DIR__ . '/' );
 
  // Passwort-Datei
  define ( 'PASSWORD_FILE', STORAGE_DIR . '.htpasswd' );
 
  // Gruppen-Datei
  define ( 'GROUP_FILE', STORAGE_DIR . '.htgroup' );  
 
  // Denied-Datei:
  define ( 'DENIED_USERS_FILE', STORAGE_DIR . '.htdeniedusers' );  
 
  // Benutzer, die nicht gelöscht werden können. Liste, mit Komma getrennt
  define ( 'USERS_NODELETE', 'root,adm' );    
  // Gruppen, die nicht gelöscht werden können. Liste, mit  Komma getrennt
  define ( 'GROUPS_NODELETE', 'root,adm' );      
 
  define ( 'NAMES_PATTERN', '[0-9A-Za-z@_.-]{3,}' );
  define ( 'NAMES_PATTERN_DESCR','Gültig sind alle Buchstaben aus dem ASCII-Zeichensatz und Ziffern sowie die Zeichen @, -, _  und der Punkt, die Eingabe muss mindestens 3 Zeichen lang sein:' );
 
  define('MIN_PASSWORD_LENGTH', 8);
  $password_patterns[]='.{'. MIN_PASSWORD_LENGTH .'}'; # Länge: Mindestens MIN_PASSWORD_LENGTH Zeichen
  $password_patterns[]='[A-Z]{1,}'; # gr. Buchstaben
  $password_patterns[]='[a-z]{1,}'; # kl, Buchstaben
  $password_patterns[]='[0-9]{1,}'; # Ziffern
  $password_patterns[]='[^A-Za-z0-9]{1,}'; # Sonderzeichen
  
  define ( 'PASSWORD_PATTERN_DESCR','Das Passwort muss mindestens ' . MIN_PASSWORD_LENGTH . ' Zeichen lang sein und Buchstaben, Ziffern sowie Sonderzeichen enthalten, es darf nicht mit einem Leerzeichen beginnen oder enden.' );
 
    // Trennzeichen der Passwortdatei, default ist ":"
  define( 'HTPASSWD_SEPARATOR' , ':');
  // Trennzeichen der Extra-Informationen in der Passwortdatei, default ist ","
  define( 'HTPASSWD_EXTRA_SEPARATOR' , ':');
 
  // Trennzeichen der Gruppendatei zwischen Gruppe und Benutzerliste, default ist ":"
  define( 'HTGROUP_SEPARATOR' , ':');
  // Trennzeichen der Gruppendatei zwischen den Benutzern, default ist ","
  define( 'HTGROUP_USER_SEPARATOR' , ',');
 
 
  #######################################################################################################################
  #                                                                                                                     #
  #                                               AB HIER NICHTS MEHR ÄNDERN!                                           #
  #                                           Prüfungen, automatische Einstellungen                                     #
  #                                                                                                                     #
  #######################################################################################################################

 
  # Kompatibilität zu älteren Apache/PHP-Versionen, welche $_SERVER['REQUEST_SCHEME'] nicht liefern:
  if (! isset($_SERVER['REQUEST_SCHEME']) ) {
     if ( isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] ) {
       $_SERVER['REQUEST_SCHEME']='https';
     } else {
       $_SERVER['REQUEST_SCHEME']='http';
     }
  }
 
  # zwingende Rechteeinschränkung für shared-Hosting 
  if (SHARED_HOSTING) {
    define('AUTH_UMASK',    0077);
    define('AUTH_DIR_MOD',  0700);
    define('AUTH_FILE_MOD', 0600); # Auf Vorrat für die künftige Benutzung :)
  } else {
    define('AUTH_UMASK',    0007);
    define('AUTH_DIR_MOD',  0770);
    define('AUTH_FILE_MOD', 0660); # Auf Vorrat für die künftige Benutzung :)
  }
 
  define ('JSON_PASSWORD_PATTERN', json_encode($password_patterns) );
 
  if (STORAGE_METHOD == 'csv' or STORAGE_METHOD == 'json') {
    if ( '' == STORAGE_DIR ) {
      trigger_error('UPS! STORAGE_DIR darf nicht leer sein. Setzen Sie einen Punkt, wenn Sie die Daten WIRKLICH im aktuellen Verzeichnis speichern wollen!', E_USER_ERROR);
    }
    $ar=array(PASSWORD_FILE, GROUP_FILE, DENIED_USERS_FILE);
    foreach ($ar as $file) {
      if (! is_file(PASSWORD_FILE) ) {
        trigger_error('UPS! '.$file.' gibt es nicht.', E_USER_ERROR);  
      }
      if (! is_readable(PASSWORD_FILE) ) {
        trigger_error('UPS! '.$file.' kann nicht gelesen werden.', E_USER_ERROR);  
      }      
    }
  }
 
 
if ( defined('SESSION_FILE_DIR') ) {
    if (! is_dir( SESSION_FILE_DIR ) ) {
       $dummy=umask(0077);
       if (! mkdir(SESSION_FILE_DIR . '/', 0700, true) ) {
         trigger_error('Fatal: Unmöglich, das Verzeichnis für die Session-Dateien anzulegen.', E_USER_ERROR);
       }
       if (! chmod( SESSION_FILE_DIR, 0700 ) ) {
	  trigger_error('Fatal: Unmöglich, die Rechte für das Verzeichnis mit Session-Dateien zu setzen.', E_USER_ERROR);
       }
       if (! file_put_contents(SESSION_FILE_DIR . '/' . '.htaccess', 'deny from all') ) {
          trigger_error('Fatal: Unmöglich, das Verzeichnis für die Session-Dateien zu sperren.', E_USER_ERROR);  
       }  
    }
    if (! is_writable(SESSION_FILE_DIR) ) {
      trigger_error('Fatal: Es ist unmöglich die Session-Dateien anzulegen.', E_USER_ERROR);
    }
}
 
ini_set('session.gc_maxlifetime', SESSION_MAX_IDLE_TIME);
ini_set('session.save_handler', 'files');
session_save_path(SESSION_FILE_DIR);
ini_set('output_buffering', '1');
ini_set('session.use_trans_sid', '0');
ini_set('session.use_cookies' , '1' );
ini_set('session.use_only_cookies' , '1');
ini_set('session_cache_limiter', 'private');
header('access-control-allow-origin: ' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/');
session_set_cookie_params (SESSION_MAX_IDLE_TIME);
/* ?> Nicht setzen, sonst wird ggf. ein oder mehrere Zeichen gesendet, was Weiterleitungen stört */