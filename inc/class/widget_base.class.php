<?php
/*
 *  Copyright (C) 2009 Nouweo
 *  
 *  Nouweo is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *  
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *  
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

abstract class widget_base {
    
    abstract public function render();
    
    public function __construct($args='')
    {
        include(PATH_ROOT.'lang/'.Nw::$site_lang.'/widgets.php');
        $this->args = self::parseArgs($args);
    }
    
    protected function checkArgs($args)
    {
        foreach($args as $arg)
            if (!isset($this->args[$arg]))
                return False;

        return True;
    }
    
    private static function parseArgs($args)
    {
        # $args --> (string) "key=value, otherkey= othervalue"
        $return = array();
        
        if(empty($args))
            return $return;
        
        $args = array_map('trim', explode(',', $args));
        
        foreach($args as $str)
        {
            list($key, $value) = array_map('trim', explode('=', $str));
            $return[$key] = $value;
        }
        
        return $return;
    }
}
