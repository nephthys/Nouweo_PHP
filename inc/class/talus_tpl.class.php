<?php
/**
 * Moteur de gestion de TPLs
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
 * @begin 23/12/2006, Talus
 * @last 22/01/2009, Talus
 */

class Talus_TPL {
    /**
     * Chemin des Templates
     * 
     * @var string
     * @see self::getRootDir()
     */
    private $_root = './';
    
    /**
     * Objet Talus_TPL_Cache (Cache des TPLs)
     *
     * @see Talus_TPL_Cache
     * @var Talus_TPL_Cache
     */
    private $_cache = null;
    
    /**
     * Objet Talus_TPL_Compiler (Compilateur des TPLs)
     *
     * @see Talus_TPL_Compiler
     * @var Talus_TPL_Compiler
     */
    private $_compiler = null;
    
    /** 
     * Contenu des blocs.
     * 
     * @see self::getBlock()
     * @see self::$vars
     * @var array
     */
    private $_blocks = array(
            '.' => array(
    	           0 => array()
                )
        ); 
    
    /**
     * Variables globales (définies dans le block root)
     * 
     * @see self::$_blocks
     * @var ref
     */
    public $vars = array();
	
    /**
     * Infos sur le Template à parser.
     * 
     * @var array
     */
    private $_infos = array(); 
	
    /**
     * Liste des fichiers utilisés.
     * 
     * @var string
    */
    private $_files = array();
	
    /**
     * Contient le fichier à compiler (cache 1), lors du parsage 
     * 
     * @var string
     */	
    private $_tpl = null; 
	
    /**
     * Constructeur des Templates.
     * 
     * @param string $root Le dossier contenant les templates.
     * @param string $cache Le dossier contenant le cache.
     * @return void
     */
    public function __construct($root = './', $cache = './cache/'){
    	// -- Destruction du cache des fichiers de PHP.
    	clearstatcache();
    	
    	// -- Objets à instancier pour Talus' TPL
    	$this->_cache = Talus_TPL_Cache::getInstance();
    	$this->_compiler = Talus_TPL_Compiler::getInstance();
    	
    	// -- Un petit alias....
    	$this->vars = &$this->_blocks['.'][0];
    	
    	// -- Et on selectionne le dossier du thème à utiliser :3
    	$this->setDir($root, $cache);
    }
	
    /**
     * Permet de choisir le dossier contenant les tpls.
     * 
     * @param string $root Le dossier contenant les templates.
     * @param string $cache Le dossier contenant le cache des tpls.
     * @return void
     * @since 1.5.1
     */
    public function setDir($root = './', $cache = './cache/'){
        // -- On ampute le root du slash final, si il existe.
        $root = rtrim($root, '/');
        
        // -- Le dossier existe-t-il ?
        if (!is_dir($root)) {
            exit('Talus_TPL->set_dir :: Le répertoire <b>' . $root . '</b> n\'existe pas.');
        }
        
        $this->_root = $root;
        $this->_cache->setDir($cache);
    }
	
    /**
     * Permet de choisir le dossier contenant les tpls. Méthode Dépréciée
     * 
     * @param string $root Le dossier contenant les templates.
     * @param string $cache Le dossier contenant le cache des tpls.
     * @return void
     *
     * @deprecated 1.5.1
     */
    public function set_dir($root = './', $cache = './cache/'){
        $this->setDir($root, $cache);
    }
	
    /**
     * Ajoute un fichier à l'instance des templates.
     * 
     * @param string $file nom du fichier à ajouter.
     * @return void
     */
    private function _setFile($file){
        // -- Le fichier n'existe pas : on renvoi une erreur fatale.
        if (!is_file($this->getRootDir() . '/' . $file)) {
            exit('Talus_TPL->set_file :: Le modèle <em>' . $file . '</em> n\'existe pas.');
        }
        
        $this->_infos[$file] = array(
                'last_modif' => filemtime($this->getRootDir() . '/' . $file),
                'included' => array(),
            );
        
        $this->_files[] = $file;
    }
	
    /**
     * Définit une ou plusieurs variable.
     * 
     * @param mixed $var Variable(s) à ajouter
     * @param mixed $value Valeur de la variable à ajouter si $var est une chaine de caractères.
     * @return void
     * @since 1.3.0
     */
    public function set($var, $value = ''){
        if (is_array($var)) {
            $this->vars = array_merge($this->vars, $var);
        } else {
            $this->vars[$var] = $value;
        }
    }
    
    /**
     * Définit une variable par référence.
     * 
     * @param mixed $var Nom de la variable à ajouter
     * @param mixed &$value Valeur de la variable à ajouter.
     * @return void
     * @since 1.5.1
     */
    public function setRef($var, &$value){
        $this->vars[$var] = &$value;
    }
    
    /**
     * Définit une variable par référence. Méthode Dépréciée
     * 
     * @param mixed $var Nom de la variable à ajouter
     * @param mixed &$value Valeur de la variable à ajouter.
     * @return void
     *
     * @deprecated 1.5.1
     */
    public function set_ref($var, &$value){
        $this->setRef($var, $value);
    }
    
    /**
     * Détruit des variables
     * 
     * @param string $var,... Variables à détruire.
     * @access public
     * @return void
     * @since 1.5.1
     */
    public function unsetVars(){
        foreach (func_get_args() as $var){
            unset($this->vars[$var]);
        }
    }

    /**
     * Détruit des variables. Méthode dépréciée
     * 
     * @param string $var,... Variables à détruire.
     * @access public
     * @return void
     *
     * @deprecated 1.5.1
     */
    public function unset_var(){
        call_user_func(array($this, 'unsetVars'), func_get_args());
    }
    
    /**
     * Permet d'ajouter une itération d'un bloc et de ses variables
     * 
     * @param string $block Nom du bloc à ajouter.
     * @param array|string $ary_vars Variable(s) à assigner à ce bloc
     * @param string $value Valeur de la variable si $ary_vars est une chaine de caractères.
     * @return bool
     * @since 1.4.0
     * 
     * @deprecated 1.5.1
     */
    public function set_block($block, $ary_vars, $value = ''){
        $this->setBlock($block, $ary_vars, $value);
    }
    
    /**
     * Permet d'ajouter une itération d'un bloc et de ses variables
     * 
     * @param string $block Nom du bloc à ajouter.
     * @param array|string $ary_vars Variable(s) à assigner à ce bloc
     * @param string $value Valeur de la variable si $ary_vars est une chaine de caractères.
     * @return bool
     * @since 1.5.1
     */
    public function setBlock($block, $ary_vars, $value = ''){
        if (!is_array($ary_vars)) {
            $ary_vars = array($ary_vars => $value);
        }
        
        // -- On récupere tous les blocs, le nombre de blocs, et on met une référence sur la variable globale des blocs.
        $blocs = explode('.', $block);
        $cur_bloc = array_pop($blocs);
        $actuel = &$this->_blocks;
        
        // -- On parcourt chaque element de $actuel, et on change le groupe de fin. On peut ainsi accéder aux variables du bloc désiré :)
        foreach ($blocs as &$bloc) {
            if (!isset($actuel[$bloc])) {
                echo '<strong>Talus_TPL->assign_block_vars ::</strong> Le bloc <em>' . $bloc . '</em> (' . $block . ') n\'est pas défini.';
                return false;
            }
            
            $actuel = &$actuel[$bloc];
            $actuel = &$actuel[count($actuel) -  1];
        }
        
        if (!isset($actuel[$cur_bloc])) {
            $actuel[$cur_bloc] = array();
            $nb_rows = 0;
        } else {
            $nb_rows = count($actuel[$cur_bloc]);
        }
        
        /*
         * Variables spécifiques aux blocs (inutilisables autre part) :
         * 
         * CURRENT : Itération actuelle du bloc.
         * FIRST : Est-ce la première itération (true/false) ?
         * LAST : Est-ce la dernière itération (true/false) ?
         * SIZE_OF : Taille totale du bloc (Nombre de répétitions totale)
         */
        $ary_vars['CURRENT'] = $nb_rows + 1;
        $ary_vars['SIZE_OF'] = 0;
        
        /*
         * On peut être à la première itération ; mais ce qui est sur, c'est
         * qu'on est forcément à la dernière itération.
         * 
         * Si le nombre d'itération est supérieur à 0, alors ce n'est pas la
         * première itération, et celle d'avant n'était pas la dernière. 
         */
        $ary_vars['FIRST'] = true;
        $ary_vars['LAST'] = true;
        
        if ($nb_rows > 0) { 
            $ary_vars['FIRST'] = false;
            $actuel[$cur_bloc][$nb_rows - 1]['LAST'] = false;
            
            /* 
             * Liaison de la valeur de SIZE_OF par référence à l'itération
             * précédente (Toutes les valeurs de SIZE_OF pour toutes les
             * itérations sont les mêmes)
             */
            $ary_vars['SIZE_OF'] = &$actuel[$cur_bloc][0]['SIZE_OF'];
        }
        
        $ary_vars['SIZE_OF']++;
        $actuel[$cur_bloc][] = $ary_vars;        
        
        return true;
    }
	
    /** 
     *  Parse l'ensemble du TPL.
     * 
     *  @param  mixed $tpl TPL concerné (vide si non spécifié)
     *  @return void
     */
    public function parse($tpl = ''){
        // -- Erreur critique si vide
        if (empty($tpl)) {
            exit('Talus_TPL->parse :: Aucuns modèle renseigné !');
        }
        
        // -- Le fichier n'est pas déclaré ? On le déclare !
        if (!in_array($tpl, $this->_files)) {
            $this->_setFile($tpl);
        }
        
        $this->_tpl = $tpl;
        $this->_cache->setFile($this->_tpl, 0);
        
        // -- Si le cache n'existe pas, ou n'est pas valide, on le met à jour.
        if (!$this->_cache->isValid($this->_infos[$this->_tpl]['last_modif'])) {
            $this->_cache->put($this->_compiler->compile(file_get_contents($this->getRootDir() . '/' . $this->_tpl)));
        }
        
        $this->_cache->exec($this);
    }
	
    /**
     * Parse le TPL, mais renvoi directement le résultat de celui-ci (entièrement parsé, et donc déjà executé par PHP).
     * 
     * @param string $tpl Nom du TPL à parser.
     * @param integer $ttl Temps de vie (en secondes) du cache de niveau 2
     *
     * @return string
     * @todo Cache de niveau 2 ??
     */
    public function pparse($tpl = '', $ttl = 0){
        ob_start();
        $this->parse($tpl);
        return ob_get_clean();
    }
    
    /**
     * Parse une chaine de caractères, et retourne son contenu
     *
     * @param string $str chaine de caractère à parser
     * @return string
     * @since 1.5.0
     */
    public function sParser($str){
        return $this->_compiler->compile($str);
    }
	
    /**
     * Inclue un TPL : Le parse si nécessaire
     * 
     * @param string $file Fichier à inclure.
     * @param bool $once N'inclure qu'une fois ?
     * @param integer $lifetime Temps de vie en secondes du cache
     *
     * @return string
     * @see Talus_TPL_Compiler::compile()
     * @todo Cache de niveau 2 ?
     */
    public function includeTpl($file, $once = false, $lifetime = 0){	
        if (!is_file($this->getRootDir() . '/' . $file)) {
            echo '<strong>Templates->include_tpl :: </strong> Le fichier ' . $file . ' n\'existe pas.';
            return false;
        }
        
        $current_tpl = $this->_tpl;
        
        /*
         * Si un fichier ne doit être présent qu'une seule fois, on regarde si
         * il a déjà été inclus au moins une fois.
         * 
         * Si oui, on ne l'inclue pas ; 
         * Si non, on l'ajoute à la pile des fichiers inclus.
         */
        if ($once){
            if (in_array($file, $this->_infos[$current_tpl]['included'])) {
                return false;
            } else {   
                $this->_infos[$current_tpl]['included'][] = $file;
            }
        }
        
        $data = $this->pparse($file, (int)$lifetime);
        
        // -- Remise du vrai alias pour compiler "le reste", et affichage du contenu du tpl
        $this->_tpl = $current_tpl;
        echo $data;
        
        return true;
    }
    
    /**
     * Accessor pour $this->_root
     *
     * @return string
     */
    public function getRootDir(){
        return $this->_root;
    }
    
    /**
     * Accessor pour le dossier de cache.
     *
     * @return string
     */
    public function getCacheDir(){
        return $this->_cache->getDir();
    }
    
    /**
     * Getter pour $this->_blocks
     * 
     * @param string $block Bloc à récupérer
     * @return array
     */
    public function getBlock($block = '.'){
        return isset($this->_blocks[$block]) ? $this->_blocks[$block] : null;
    }
}

/**
 * EOF
 */
