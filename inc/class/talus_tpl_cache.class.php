<?php
/**
 * Contient les fonctions de caches FTP, nécessaires à Talus' TPL.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *      
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *      
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA. 
 *
 * @package Talus' TPL
 * @author Baptiste "Talus" Clavié <talusch@gmail.com>
 * @copyright ©Talus, Talus' Works 2006+
 * @link http://www.talus-works.net Talus' Works
 * @license http://www.gnu.org/licenses/lgpl.html LGNU Public License 2+
 * @begin 02/02/2008, Talus
 * @last 20/11/2008, Talus
 */

// -- Constantes pour les fichiers
if (!defined('PHP_EXT')) {
    define('PHP_EXT', substr(__FILE__, strrpos(__FILE__, '.') + 1));
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
 * @last 01/10/08, Talus <talusch@gmail.com>
 * @since 1.4.0
 */
class Talus_TPL_Cache {
    /**
     * Répertoire du cache
     *
     * @var string
     */
    private $_dir = './cache/';
    
    /**
     * Fichier à mettre en cache
     *
     * @var string
     */
    private $_file = 'dummy.html';
    
    /**
     * Timestamp de dernière modification du cache
     *
     * @var integer
     */
    private $_filemtime = 0;
    
    /**
     * Taille du fichier du cache
     *
     * @var integer
     */
    private  $_filesize = 0;
    
    /**
     * Instance en cours
     *
     * @var Talus_TPL_Cache
     * @since 1.5.0
     */
    private static $_instance = null;
    
    /**
     * Constructeur & Cloneur ; Certes, ils ne font rien, mais ils sont là pour
     * éviter de pouvoir instancier plusieurs fois la classe Talus_TPL_Cache (il
     * n'y a aucuns sens de l'instancier plusieurs fois...)
     *
     * @see http://fr.wikipedia.org/singleton
     * @since 1.5.0
     */
    private function __construct(){}
    private function __clone(){}
    
    /**
     * Pattern Singleton ; si l'instance n'a pas été démarrée, on la démarre...
     * Sinon, on renvoit l'objet déjà créé.
     *
     * @return Compile_Talus_TPL
     */
    public static function getInstance(){
        if (is_null(self::$_instance)){
            self::$_instance = new Talus_TPL_Cache;
        }
        
        return self::$_instance;
    }
    
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
        
        $this->_dir = $dir;
    }
    
    /**
     * Accessor pour $this->_dir
     *
     * @return unknown
     */
    public function getDir(){
        return $this->_dir;
    }
    
    /**
     * Définit le fichier à stocker
     *
     * @param string $file Nom du fichier à stocker
     * @return void
     */
    public function setFile($file) {        
        $this->_file = trim(str_replace('/', '.', $file), '.') . '.' . PHP_EXT;
        
        if (is_file($this->_dir . '/' . $this->_file)) {
            $this->_filemtime = filemtime($this->_dir . '/' . $this->_file);
            $this->_filesize = filesize($this->_dir . '/' . $this->_file);
        } else {
            $this->_filemtime = 0;
            $this->_filesize = 0;
        }
    }
    
    /**
     * Indique si le cache est toujours valide
     *
     * @param integer $time Timestamp de dernière modif du fichier
     * @return boolean
     */
    public function isValid($time) {
        return $this->_filemtime >= abs($time) && $this->_filesize > 0;
    }
    
    /**
     * Ecrit le contenu dans le cache
     *
     * @param string $data Données à écrire
     * @return boolean
     */
    public function put($data) {
        $flock = @fclose(fopen($this->_dir . '/__tpl_flock__.' . sha1($this->_dir . '.' . $this->_file), 'x'));
        
        if (!$flock){
            echo 'Talus_TPL->cache->put :: Ecriture en cache impossible (une 
            écriture est déjà en cours, ou vous n\'avez pas les permissions !)';
            
            return false;
        }
        
        file_put_contents($this->_dir . '/' . $this->_file, $data);
        chmod($this->_dir . '/' . $this->_file, 0664);
        
        //if (!is_file())
        
        unlink($this->_dir . '/__tpl_flock__.' . sha1($this->_dir . '.' . $this->_file));
        
        return true;
    }
    
    /**
     * Execute le contenu du cache
     *
     * @param Talus_TPL $tpl Objet TPL à utiliser lors de la lecture du cache
     */
    public function exec(Talus_TPL $tpl) {
        include $this->_dir . '/' . $this->_file;
    }
}

/**
 * EOF
 */
