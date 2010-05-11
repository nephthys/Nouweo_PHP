<?php
/**
 * Liste des filtres pour Talus' TPL.
 * 
 * Si vous désirez rajouter un filtre, ajoutez simplement une méthode 
 * publique statique qui fait les opérations à faire sur la variable,
 * et ce filtre pourra être considéré comme tel par le compilteur de 
 * Talus_TPL (pensez à regénerer votre cache une fois cette opération
 * faite, pour actualiser le code compilé...)
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
 * @last 02/10/2008, Talus
 * @since 1.5.0
 */

abstract class Talus_TPL_Filters {
    /**
     * Arrondi la valeur donnée à l'entier supérieur
     *
     * @param string $arg
     * @return string
     */
    public static function ceil($arg){
        return (string) ceil((int) $arg);
    }
    
    /**
     * Arrondi la valeur à l'entier inférieur
     *
     * @param string $arg
     * @return string
     */
    public static function floor($arg){
        return (string) floor((int) $arg);
    }
    
    /**
     * Encode les caractères html spéciaux de la variable
     *
     * @param string $arg
     * @return string
     */
    public static function protect($arg){
        return htmlspecialchars($arg);
    }
    
    /**
     * Met le contenu de la variable en MAJUSCULE
     *
     * @param string $arg
     * @return string
     */
    public static function capitalize($arg){
        return mb_strtoupper($arg);
    }
    
    /**
     * Met le contenu de la variable en minuscule
     *
     * @param string $arg
     * @return string
     */
    public static function minimize($arg){
        return mb_strtolower($arg);
    }
    
    /**
     * Met la première lettre d'une variable en Majuscule
     *
     * @param string $arg
     * @return string
     */
    public static function ucfirst($arg){
        return ucfirst($arg);
    }
    
    /**
     * Met la première lettre d'une variable en minuscule
     *
     * @param string $arg
     * @return string
     */
    public static function lcfirst($arg){
        $arg[0] = mb_strtolower($arg[0]);
        
        return $arg;
    }
    
    /**
     * Met la première lettre de chaques mots d'une variable en Majuscule
     *
     * @param string $arg
     * @return string
     */
    public static function ucwords($arg){
        return ucwords($arg);
    }
    
    /**
     * Change la casse d'une variable
     *
     * @param string $arg
     * @return string
     */
    public static function invertCase($arg){
        for ($i = 0, $length = strlen($arg); $i < $length; $i++){
            $tolower = mb_strtolower($arg[$i]);
            $arg[$i] = $arg[$i] == $tolower ? mb_strtoupper($arg[$i]) : $tolower;
        }
        
        return $arg;
    }
    
    /**
     * Transforme les sauts de lignes en <br />
     *
     * @param string $arg
     * @return string
     */
    public static function nl2br($arg){
        return nl2br($arg);
    }
}

/** EOF /**/
