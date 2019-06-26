<?php
if (!function_exists('exec_in_background')) {
    /**
     * Ejecuta un comando del sistema en el background
     *
     * @param string $cmd
     * Cadena de comando para ejecutar
    ***/
    function exec_in_background($cmd){
      if (substr(php_uname(), 0, 7) == "Windows"){
        pclose(popen("start /B ". $cmd, "r"));
      }
      else exec($cmd . " > /dev/null &");
    }
}
