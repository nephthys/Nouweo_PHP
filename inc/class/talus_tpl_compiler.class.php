<?php
/**
 * Compilateur de Talus' TPL.
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
 * @package Talus' Works
 * @author Baptiste "Talus" Clavié <talusch@gmail.com>
 * @copyright ©Talus, Talus' Works 2008+
 * @link http://www.talus-works.net Talus' Works
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License 2+
 * @begin 01/10/2008, Talus
 * @last 10/01/2009, Talus
 */

class Talus_TPL_Compiler {
    private static $_instance = null;
    
    /**
     * Constructeur & Cloneur ; Certes, ils ne font rien, mais ils sont là pour
     * éviter de pouvoir instancier plusieurs fois la classe Talus_TPL_Compiler 
     * (il n'y a aucuns sens de l'instancier plusieurs fois...)
     *
     * @see http://fr.wikipedia.org/singleton
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
            self::$_instance = new Talus_TPL_Compiler;
        }
        
        return self::$_instance;
    }
    
    /**
     * Transforme une chaine en syntaxe TPL vers une syntaxe PHP.
     * 
     * @param string $compile TPL à compiler
     * @return string
     */
    public function compile($compile){
        $compile = str_replace('<?' ,'<?php echo \'<?\'; ?>', $compile);
        $compile = preg_replace('`/\*.*?\*/`s', '', $compile);
        
        // -- Utilisation de filtres (Marche presque tout le temps, sauf pour {MAVAR[{AUTRVAR}]|f1|f2...})
        $compile = preg_replace_callback('`\{(\$?[][a-zA-Z_\x7f-\xff.,]+?)\|((?:[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\|?)+)}`', array($this, '_filters'), $compile);
        
        // -- Appels de fonctions 
        $compile = preg_replace_callback('`<call name="([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)"((?: arg="[^"]*?")*) />`', array($this, '_callFunction'), $compile);
        
        // -- Les blocs et leurs variables (en incluant les arrays)
        $compile = preg_replace_callback('`<block name="([a-z_\x7f-\xff][a-z0-9_\x7f-\xff]*)(?:\.([a-z0-9_]+))?">`', array($this, '_compileBlock'), $compile);
        
        // -- Widget 
        $compile = preg_replace_callback('`<widget id="([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)"((?: args="[^"]*?")*) />`', array($this, '_callWidget'), $compile);
        
        // -- Regex
        $ary_regex = array(
                // -- Inclusions
                '`<include tpl="(\{\$(?:[a-z0-9_]+\.)?[A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*(?:\[(?!]})(?:.*?)])?})"[\s]+/>`s' => '<?php $tpl->includeTpl($1, false, 0); ?>',
                '`<include tpl="(.+?\.html)"[\s]+/>`s' => '<?php $tpl->includeTpl(\'$1\', false, 0); ?>',
                '`<include tpl="(\{\$(?:[a-z0-9_]+\.)?[A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*(?:\[(?!]})(?:.*?)])?})"[\s]+once="(true|false)"[\s]+/>`s' =>  '<?php $tpl->includeTpl($1, $2, 0); ?>', 
                '`<include tpl="(.+?\.html)"[\s]+once="(true|false)"[\s]+/>`s' => '<?php $tpl->includeTpl(\'$1\', $2, 0); ?>',
                
                // -- Déclaration de variables
                '`<var name="([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)" value="([^"]+)"[\s]*/>`' => '<?php $tpl->vars[\'$1\'] = \'$2\'; ?>',
                '`<var name="([a-z_\x7f-\xff][a-z0-9_\x7f-\xff]*)\.([A-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)" value="([^"]+)"[\s]*/>`' => '<?php $__tpl_blocs[\'$1\'][\'$2\'] = \'$3\'; ?>', 
                
                // -- Foreachs
                '`<foreach ary="\{\$([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)}">`i' => '<?php foreach ({$$1} as $__tpl_foreach_key[\'$1\'] => $__tpl_foreach_value[\'$1\']) : ?>',
                '`<foreach ary="\{((?:(?:VALUE,)?\$[A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)(?:\[(?!]})(?:.*?)])?)}" as="\{\$([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)}">`i' => '<?php foreach ({$1} as $__tpl_foreach_key[\'$2\'] => $__tpl_foreach_value[\'$2\']) : ?>', 
                '`\{KEY,([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)}`i' => '<?php echo $__tpl_foreach_key[\'$1\']; ?>',
                '`\{KEY,\$([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)}`i' => '$__tpl_foreach_key[\'$1\']',
                '`\{VALUE,([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)(\[(?!]})(?:.*?)])?}`' => '<?php echo $__tpl_foreach_value[\'$1\']$2; ?>',
                '`\{VALUE,\$([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)(\[(?!]})(?:.*?)])?}`' => '$__tpl_foreach_value[\'$1\']$2', 
                
                // -- Constantes
                '`\{__([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)__}`i' => '<?php echo $1; ?>',
                '`\{__\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)__}`i' => '$1', 
                
                // -- Variables simples
                '`\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)(\[(?!]})(?:.*?)])?}`' => '<?php echo $tpl->vars[\'$1\']$2; ?>',
                '`\{\$([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)(\[(?!]})(?:.*?)])?}`' => '$tpl->vars[\'$1\']$2',
                
                // -- Variables Blocs
                '`\{([a-z_\x7f-\xff][a-z0-9_\x7f-\xff]*)\.([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)(\[(?!]})(?:.*?)])?}`' => '<?php echo $__tpl_blocs[\'$1\'][\'$2\']$3; ?>',
                '`\{\$([a-z_\x7f-\xff][a-z0-9_\x7f-\xff]*)\.([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)(\[(?!]})(?:.*?)])?}`' => '$__tpl_blocs[\'$1\'][\'$2\']$3',

                // -- Conditions
                '`<if cond(?:ition)?="(?!">)(.+?)">`' => '<?php if ($1) : ?>',
                '`<elseif cond(?:ition)?="(?!" />)(.+?)" />`' => '<?php elseif ($1) : ?>'
            );
        
        // -- Remplacement des regex simples
        $compile = preg_replace(array_keys($ary_regex), array_values($ary_regex), $compile);
            
        // -- Les définitions de fonctions
        $compile = preg_replace_callback('`<function name="([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)"((?: arg="[A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*")*)>(.+?)</function>`s', array($this, '_defineFunction'), $compile);
        
        // -- Les str_replace (moins de ressources que les foreach) !
        $compile = str_replace(array(
                '</block>', '<blockelse />', '</foreach>',
                '<else />', '</if>',
                '{\\'
            ), array(
                '<?php } endif; ?>', '<?php } else : if (true) { ?>', '<?php endforeach; ?>',
                '<?php else : ?>', '<?php endif; ?>',
                '{'
            ), $compile);
        
        // -- Nettoyage du code
        $compile = str_replace('?><?php', '', $compile);
        
        // -- On retourne le code compilé.
        return $compile;
    }
    
    private function _callWidget(array $match)
    {
        static $included_widgets = array();
        $php = '<?php ';
        
        if(!empty($match[1]))
        {
            if(!file_exists(Nw::$assets['dir_widgets'].'w_'.$match[1].'.php'))
                return '';
            
            if(!in_array($match[1], $included_widgets))
            {
                $php .= 'include_once $tpl->vars[\'_ASSETS_\'][\'dir_widgets\'].\'w_'.$match[1].'.php\'; ';
                $included_widgets[] = $match[1];
            }
            
            $widget_identifier = '$widget_w_'.$match[1].'_'.uniqid();
            $args = (!empty($match[2])) ? $match[2] : '';
            
            $php .= $widget_identifier.' = new w_'.$match[1].'(\''.$args.'\'); ';
            $php .= 'echo '.$widget_identifier.'->render(); ';
        }
        
        return $php.' ?>';
    }
        
    /**
     * Compile un bloc
     * 
     * @param  array $match  Array contenant les captures des blocs (cf Ligne 92)
     * @see compile()
     * @return string
     */
    private function _compileBlock(array $match){
        /*
         * Il y a un bloc parent ; Il nous faut donc utiliser une des variables
         * temporaires créées par le foreach du bloc parent, et de substituer
         * $match[1], pour le bloc actuel.
         * 
         * Sinon, Il nous faut juste récupérer le bloc à la racine.
         */
        if (!empty($match[2])) { // -- Il y a un bloc parent ; ca change donc un peu...
            $bloc = '$__tpl_blocs[\'' . $match[1] . '\'][\'' . $match[2] . '\']';
            $cond = 'isset(' . $bloc . ')';
            
            $match[1] = $match[2];
        } else {
            $cond = $bloc = '$tpl->getBlock(\'' . $match[1] . '\')';
        }
        
        return '<?php if (' . $cond . ') : foreach (' . $bloc . ' as $__tpl_blocs[\'' . $match[1] . '\']){ ?>';
    }
    
    /**
     * Parse les déclarations de fonctions
     * 
     * @param array $matches Capture (cf ligne 70)
     * @see compile()
     * @return string
     */
    private function _defineFunction(array $matches){
        $php = '<?php function __tpl_' . $matches[1] . '(Talus_TPL $tpl, ';
        
        // -- Demande d'arguments...
        if (!empty($matches[2])) {
            $args = explode(' ', ltrim($matches[2]));
            foreach ($args as $arg) {
                $php .= '$' . mb_substr($arg, 5, -1) . ', ';
            }
        }
        
        $php = rtrim($php, ', ') . '){ ?>';
        
        $script = preg_replace('`\$tpl->vars\[\'([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\']`', '$$1', $matches[3]);
        $script = preg_replace('`\$GLOB,([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)`', '$tpl->vars[\'$1\']', $script );
        
        return $php . $script . ' <?php } ?>';
    }
    
    /**
     * Parse les appels de fonctions
     * @param array $matches Capture (cf ligne 113)
     * @see compile()
     * @return string
     */
    private function _callFunction(array $matches){
        $php = '<?php __tpl_' . $matches[1] . '($tpl, ';
        
        if (!empty($matches[2])) {
            $args = explode('" arg="', substr(ltrim($matches[2]), 5, -1));
            
            foreach ($args as &$arg ){
                //Argument vide ?
                if (empty($arg)){
                    $arg = '\'\'';
                } else {
                    // -- Si l'argument en cours d'exploration n'est pas une variable, ni un chiffre... On le quote !
                    if (($arg[0] != '{' || $arg[mb_strlen($arg) - 1] != '}') && !ctype_digit($arg)) {
                        $arg = '\'' . str_replace('\'', '\\\'', $arg) . '\'';
                    }
                }
                
                $php .= $arg . ', ';
            }           
        }
        
        return rtrim($php, ', ') . '); ?>';
    }
    
    /**
     * Filtre une var avec un ou plusieurs des filtres de Talus_TPL_Filters. (cf ligne 73)
     *
     * @param array $match Capture de la regex
     * @see Talus_TPL_Filters
     * @return string
     * @since 1.5.0DEV
     */
    private function _filters(array $match){
        /*
         * Récupération de la liste des filtres, et on l'inverse (pour appliquer
         * d'abord le premier filtre, puis le second, ...)
         */
        $filters = array_reverse(array_filter(explode('|', $match[2])));
        
        // -- Début du retour
        $return = '';
        
        // -- Compteur pour le nombre de ( ouvertes.
        $i = 0;
        
        foreach ($filters as &$filter) {
            /*
             * Le filtre n'est pas déclaré ; Dans ce cas, on affiche un message
             * d'erreur, et on l'ignore.
             */
            if (!method_exists('Talus_TPL_Filters', $filter)){
                echo "Talus_TPL_Compiler :: Le filtre \"$filter\" n'existe pas, et sera donc ignoré.<br />\n";
                continue;
            }
            
            // -- Ajout du filtre, incrémentation du nombre de (
            $return .= 'Talus_TPL_Filters::' . $filter . '(';
            $i++;
        }
        
        // -- Association de la variable, fermeture des différentes ( ouvertes
        $return .= '{$' . $match[1] . '}' . str_repeat(')', $i);
        
        /*
         * Si on souhaite afficher le tout, on force à avoir un retour de la
         * variable (de la manière habituelle : <code><?php echo ma_var; ?></code>),
         * sinon, si on souhaite un retour de variables, on laisse tel quel.
         */
        if ($match[1][0] != '$'){
            $return = '<?php echo ' . $return . '; ?>';
        } else {
            $return = preg_replace('`^(Talus_TPL_Filters::[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*\()+\{\$`',
                                   '$1{', $return, 1);
        }
        
        return $return;
    }
}

/** EOF /**/
