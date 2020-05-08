<?php

class CreateCmsTables {
    
    public function create() {
        if(enableDBCoreGeneration()) {
            $db = get_instance()->db;
            
            $query =  "CREATE TABLE IF NOT EXISTS `relations` ("
                . "`id` int(11) NOT NULL AUTO_INCREMENT, "
                . "`fromid` int(11) NOT NULL, "
                . "`fromtype` varchar(50) NOT NULL, "
                . "`field` varchar(50) NOT NULL, "
                . "`toid` int(11) NOT NULL, "
                . "`totype` varchar(50) NOT NULL, "
                . "PRIMARY KEY (`id`)) "
                . "ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20";     
            $db->query($query);
            $db->query("ALTER TABLE `relations` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=200");
            
            $query =  "CREATE TABLE IF NOT EXISTS `childrelations` ("
                . "`id` int(11) NOT NULL AUTO_INCREMENT, "
                . "`fromid` int(11) NOT NULL, "
                . "`fromtype` varchar(50) NOT NULL, "
                . "`field` varchar(50) NOT NULL, "
                . "`toid` int(11) NOT NULL, "
                . "`totype` varchar(50) NOT NULL, "
                . "PRIMARY KEY (`id`)) "
                . "ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20";     
            $db->query($query);
            $db->query("ALTER TABLE `childrelations` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=200");
            
            $query = "CREATE TABLE IF NOT EXISTS `moduletopage` (
                `pageid` int(11) NOT NULL,
                `moduleid` int(11) NOT NULL,
                `moduletype` varchar(100) NOT NULL,
                `position` smallint(6) NOT NULL,
                `template` varchar(50) NOT NULL,
                `contentzone` varchar(50) NOT NULL,
                `stringrepresentation` varchar(100) NOT NULL,
                PRIMARY KEY (`pageid`,`moduleid`,`moduletype`,`position`,`contentzone`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $db->query($query);
            
            $query = "CREATE TABLE IF NOT EXISTS `file` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `filename` varchar(100) NOT NULL,
                `filetype` varchar(50) NOT NULL,
                `originalFilename` varchar(50) NOT NULL,
                `filesize` int(11) NOT NULL,
                `imageHeight` int(11) DEFAULT NULL,
                `imageWidth` int(11) DEFAULT NULL,
                `extension` varchar(50) NOT NULL,
                `isImage` tinyint(4) NOT NULL DEFAULT '0',
                `sizes`	text,
                PRIMARY KEY (`id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38" ;
            $db->query($query);
        }
    }
}