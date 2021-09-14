<?php
require_once "autoload.inc.php";

class Session
{
    /**
     * @return bool
     * @throws SessionException Lorsqu'il y a impossibilité de modifier les entêtes HTTP
     *                             ou incohérence de l'état de session.
     */
    public static function start():void
    {
      $res=false;
      if ( session_status() !== PHP_SESSION_ACTIVE )
      {
          if(session_status() === PHP_SESSION_NONE)
          {
              if(headers_sent())
                  throw new SessionException ("Impossible de démarrer une session : des entêtes ont été envoyé");
              else{
                  session_start();
              }
          }
          else {
              throw new SessionException("Impossible de démarrer une session : les sessions sont désactivées");
          }

      }
    }



}
