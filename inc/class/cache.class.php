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

class Cache
{
    public static function Read($name, $time = 0)
    {
        $file = PATH_ROOT.'cache/'.md5($name);
        if(!self::Expires($name, $time))
        {
            return unserialize(file_get_contents($file));
        }
        else
            return false;
    }

    public static function Expires($name, $time = 0)
    {
        $file = PATH_ROOT.'cache/'.md5($name);
        return !(is_file($file) && ($time == 0 || filemtime($file) + $time <= time()));
    }

    public static function Delete($name)
    {
        $file = PATH_ROOT.'cache/'.md5($name);
        if(is_file($file))
        {
            unlink($file);
            return true;
        }
        else
            return false;
    }

    public static function Write($name, $var)
    {
        $file = PATH_ROOT.'cache/'.md5($name);
        file_put_contents($file, serialize($var));
    }
}
