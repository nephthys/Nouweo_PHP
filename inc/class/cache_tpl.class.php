<?php
/**
 * Contient les fonctions de caches FTP, nécessaires à Talus' TPL.
 *
 * Vous êtes libre d'utiliser et de distribuer ce script comme vous l'entendez, en gardant à l'esprit
 * que ce script est, à l'origine, fait par des développeurs bénévoles : Veillez donc à laisser le 
 * Copyright, au moins par respect de ceux qui ont consacré du temps à la création de ce script. 
 *
 * @package Talus' TPL
 * @author Baptiste "Talus" Clavié <talusch@gmail.com>
 * @copyright ©Talus, Talus' Works 2006+
 * @link http://www.talus-works.net Talus' Works
 * @license http://www.gnu.org/licenses/lgpl.html LGNU Public License 2+
 * @begin 02/02/2008, Talus
 * @last 23/08/2008, Talus
 */

// -- Constantes pour les fichiers
if (!defined('PHP_EXT')) {
    define('PHP_EXT', substr(__FILE__, strrpos(__FILE__, '.') + 1));
}

if (!defined('OS')) {
    define('OS', strtoupper(substr(PHP_OS, 0, 3)));
}

/**
 * Si la fonction file_put_contents existe pas, on l'invente... !
 * 
 * @param string $filename Nom du fichier.
 * @param string $content Contenu du fichier.
 * @return integer
 */
if (!function_exists('file_put_contents')) {
    function file_put_contents($filename, $content){
        $file = fopen($filename, 'w');
        
        if (!$file) {
            return -1;
        }
        
        $bytes = fwrite($file, $content);
        
        fclose($file);
        
        return $bytes;
    }
}

/**
 * Si la fonction file_get_contents existe pas, on l'invente... !
 *  
 * @param string $filename Nom du fichier.
 * @return string
 */
if (!function_exists('file_get_contents')) {
    function file_get_contents($filename){
        $file = fopen($filename, 'r');
        
        if (!$file) {
            return false;
        }
        
        $buffer = fread($file, filesize($filename));
        
        fclose($file);
        
        return $buffer;
    }
}

/**
 * Gère le cache des TPLs
 * 
 * @package Talus' TPL
 * @author Baptiste "Talus" Clavié <talusch@gmail.com>
 * @begin 23/08/08, Talus <talusch@gmail.com>
 * @last 23/08/08, Talus <talusch@gmail.com>
 */
class Cache_TPL {
    /**
     * Répertoire du cache
     *
     * @var string
     */
    public $dir = './cache/';
    
    /**
     * Fichier à mettre en cache
     *
     * @var string
     */
    private $file = 'dummy.html';
    
    /**
     * Timestamp de dernière modification du cache
     *
     * @var integer
     */
    private $filemtime = 0;
    
    /**
     * Défini le dossier de cache
     *
     * @param string $dir Chemin du cache
     * @return void
     */
    public function setDir($dir) {
        $dir = rtrim($dir, '/');
        
        if (!is_dir($dir)){
            exit('Talus_TPL->cache->setDir :: Le dossier n\'existe pas');
        }
        
        $this->dir = $dir;
    }
    
    /**
     * Définit le fichier à stocker
     *
     * @param string $file Nom du fichier à stocker
     * @return void
     */
    public function setFile($file) {        
        $this->file = trim(str_replace('/', '.', $file), '.') . '.' . PHP_EXT;
        $this->filemtime = is_file($this->dir . '/' . $this->file) ? filemtime($this->dir . '/' . $this->file) : 0;
    }
    
    /**
     * Indique si le cache est toujours valide
     *
     * @param integer $time Timestamp de dernière modif du fichier
     * @return boolean
     */
    public function valid($time) {
        return $this->filemtime >= abs($time);
    }
    
    /**
     * Ecrit le contenu dans le cache
     *
     * @param string $data Données à écrire
     * @return boolean
     */
    public function put($data) {
        $flock = @fclose(fopen($this->dir . '/__tpl_flock__.' . sha1($this->dir . '.' . $this->file), 'x'));
        
        if (!$flock){
            echo 'Talus_TPL->cache->put :: Ecriture en cache impossible (une écriture est déjà en cours)';
            return false;
        }
        
        file_put_contents($this->dir . '/' . $this->file, $data);
        chmod($this->dir . '/' . $this->file, 0664);
        
        //if (!is_file())
        
        unlink($this->dir . '/__tpl_flock__.' . sha1($this->dir . '.' . $this->file));
        
        return true;
    }
    
    /**
     * Execute le contenu du cache
     *
     * @param Talus_TPL $tpl Objet TPL à utiliser lors de la lecture du cache
     */
    public function exec(Talus_TPL $tpl) {
        include($this->dir . '/' . $this->file);
    }
}

/**
 * EOF
 */
