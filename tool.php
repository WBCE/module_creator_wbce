<?php

/**
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 3 of the License, or (at
 *   your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful, but
 *   WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 *   General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program; if not, see <http://www.gnu.org/licenses/>.
 *
 *   @author          BlackBird Webprogrammierung
 *   @copyright       2016, BlackBird Webprogrammierung
 *   @link            http://blackcat-cms.org
 *   @license         http://www.gnu.org/licenses/gpl.html
 *   @package         Module Creator
 *
 */

if (!defined('WB_PATH')) die(header('Location: index.php'));

// check permissions
if(!class_exists('admin', false)){
	$admin_header = FALSE;
	include(WB_PATH.'/framework/class.admin.php');
	$admin = new admin('admintools', 'admintools');
}
if(!$admin->get_permission('admintools')) {
	die( header('Location: ../../index.php') );
}

// list of available licenses
$licenses = array(
    'GPLv3' => 'https://www.gnu.org/licenses/gpl-3.0.txt',
    'GPLv2' => 'https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt',
    'MIT' => 'https://opensource.org/licenses/MIT',
    'WTFPL' => 'http://www.wtfpl.net/txt/copying',
    'CC Attribution 3.0 Unported' => 'https://creativecommons.org/licenses/by/3.0/',
    'CC Attribution-ShareAlike 3.0 Unported' => 'https://creativecommons.org/licenses/by-sa/3.0/',
    'CC Attribution-NoDerivs 3.0 Unported' => 'https://creativecommons.org/licenses/by-nd/3.0/',
    'CC Attribution-NonCommercial 3.0 Unported' => 'https://creativecommons.org/licenses/by-nc/3.0/',
    'CC Attribution-NonCommercial-ShareAlike 3.0 Unported' => 'https://creativecommons.org/licenses/by-nc-sa/3.0/',
    'CC Attribution-NonCommercial-NoDerivs 3.0 Unported' => 'https://creativecommons.org/licenses/by-nc-nd/3.0/',
);

$data = array(
    'new_moduletype' => 'page',
    'new_modulename' => NULL,
    'new_moduledir' => NULL,
    'new_moduledesc' => NULL,
    'new_moduleauthor' => NULL,
    'new_modulecopyright' => NULL,
    'new_modulelicense' => NULL,
);

if(isset($_SESSION['mod_mc_data'])) {
    $data = $_SESSION['mod_mc_data'];
    unset($_SESSION['mod_mc_data']);
}

if($doSave) {

    $type          = $admin->get_post('new_moduletype');
    $path          = str_replace('\\','/',$modulePath.'templates/default/dummies/'.$type);
    $modulePath    = str_replace('\\','/',$modulePath); // fix backslashes
    $relpath       = $modulePath.'templates/default/dummies/'.$type.'/';
    $result        = array();
    $modfolder     = NULL;
    $modname       = $admin->get_post('new_modulename');

    switch ($type) {
        case 'page':
        case 'tool':
            $modfolder = str_replace('\\','/',WB_PATH.'/modules/'.);
            break;
        case 'template':
            $modfolder = str_replace('\\','/',WB_PATH.'/templates/'.);
            break;
        case 'language':
            $modfolder = str_replace('\\','/',WB_PATH.'/languages/'.);
            break;
    }

    if(is_dir($modfolder)) {
        // save data to session to re-populate the form
        $_SESSION['mod_mc_data'] = $_POST;
        $admin->print_error($MESSAGE['MEDIA_DIR_EXISTS'],WB_ADMIN_URL.'/admintools/tool.php?tool=module_creator_wbce');
    }

    if(file_exists($path.'/info.php')) {
        $dirh      = dir($path);
        $files     = file_list($path);
        $dirs      = directory_list($path);
        // create module folder
        make_dir($modfolder);
        if(is_array($dirs) && count($dirs)) {
            $dir_count    = count($dirs);
            $dirs_created = 0;
            foreach(array_values($dirs) as $dir) {
                $dir = str_replace($relpath,'',$dir);
                $ok  = make_dir(WB_PATH.'/modules/'.$admin->get_post('new_modulename').'/'.$dir,OCTAL_DIR_MODE,true);
                if($ok) {
                    $dirs_created++;
                    $subfiles = file_list($path.'/'.$dir);
                    $files    = array_merge($files,$subfiles);
                }
            }
            $result[] = "<tr><td>".$MOD_MC_TEXT['MOD_DIR_FOUND'].":</td><td>$dir_count</td></tr>\n"
                      . "<tr><td>".$MOD_MC_TEXT['MOD_DIR_CREATED'].":</td><td>$dirs_created</td></tr>\n";
        }

        if(is_array($files) && count($files)) {
             $file_count    = count($files);
             $files_created = 0;
             foreach(array_values($files) as $file) {
                 $filename  = str_replace($relpath,'',$file);
                 $contents  = getFilePart($file);
                 $contents  = str_replace(
                     array(
                         '%%author%%',
                         '%%copyright%%',
                         '%%description%%',
                         '%%function%%',
                         '%%license%%',
                         '%%licensename%%',
                         '%%MOD_NAME_UC%%',
                         '%%modulename%%',
                         '%%package%%',
                     ),
                     array(
                         $admin->get_post('new_moduleauthor'),
                         $admin->get_post('new_modulecopyright'),
                         $admin->get_post('new_moduledesc'),
                         $type,
                         $licenses[$admin->get_post('new_modulelicense')],
                         $admin->get_post('new_modulelicense'),
                         strtoupper($admin->get_post('new_modulename')),
                         $admin->get_post('new_modulename'),
                         $admin->get_post('new_moduledir'),
                     ),
                     $contents
                 );
                 $fh = fopen($modfolder.'/'.$filename,'w');
                 if($fh && is_resource($fh)) {
                     fwrite($fh,$contents);
                     fclose($fh);
                     $files_created++;
                 }
             }
             $result[] = "<tr><td>".$MOD_MC_TEXT['MOD_FILES_FOUND'].":</td><td>$file_count</td></tr>\n"
                       . "<tr><td>".$MOD_MC_TEXT['MOD_FILES_CREATED'].":</td><td>$files_created</td></tr>\n";
        }
        require dirname(__FILE__).'/templates/default/result.tpl';
    }
}
else {
    $username = $admin->get_session('DISPLAY_NAME');
    require dirname(__FILE__).'/templates/default/tool.tpl';
}