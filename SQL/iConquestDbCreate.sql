CREATE TABLE IF NOT EXISTS `civil_works` (
  `ID` mediumint(9) NOT NULL COMMENT 'ID Number',
  `ranch` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Ranch',
  `small_farm` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Small Farm',
  `dock` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Dock',
  `war_factory` smallint(6) NOT NULL DEFAULT '0' COMMENT 'War Factory',
  `pipeline` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Pipeline',
  `museum` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Museum',
  `regional_mint` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Regional Mint',
  `science_inst` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Science Institute',
  `iron_mine` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Iron Mine',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `deployed` (
  `ID` int(11) NOT NULL COMMENT 'ID number',
  `inf_1_deploy` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Inf lv 1 Deployed',
  `inf_2_deploy` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Inf lv 2 Deployed',
  `inf_3_deploy` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Inf lv 3 Deployed',
  `inf_1_used` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Inf 1 Used',
  `inf_2_used` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Inf 2 Used',
  `inf_3_used` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Inf 3 Used',
  `inf_1_today` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Inf 1 Max Today',
  `inf_2_today` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Inf 2 Max Today',
  `inf_3_today` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Inf 3 Max Today',
  `armor_1_deploy` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Armor 1 Deployed',
  `armor_2_deploy` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Armor 2 Deployed',
  `armor_3_deploy` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Armor 3 Deployed',
  `armor_4_deploy` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Armor 4 Deployed',
  `armor_5_deploy` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Armor 5 Deployed',
  `armor_1_used` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Armor 1 Used',
  `armor_2_used` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Armor 2 Used',
  `armor_3_used` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Armor 3 Used',
  `armor_4_used` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Armor 4 Used',
  `armor_5_used` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Armor 5 Used',
  `armor_1_today` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Armor 1 Max Today',
  `armor_2_today` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Armor 2 Max Today',
  `armor_3_today` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Armor 3 Max Today',
  `armor_4_today` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Armor 4 Max Today',
  `armor_5_today` varchar(6) NOT NULL DEFAULT '0' COMMENT 'Armor 5 Max Today',
  `deployed_today` varchar(1) NOT NULL DEFAULT '0' COMMENT '0 = no; 1 = yes',
  `last_deploy` varchar(10) NOT NULL DEFAULT '1269542746' COMMENT 'Last Deploy Date (YYYY-MM-DD)',
  `att_used_date` varchar(10) NOT NULL DEFAULT '0' COMMENT 'Timestamp of Last Day Attacker''s Units Moved',
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `foreign_aid` (
  `ID_aid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Aid ID number',
  `ID_send` int(11) NOT NULL COMMENT 'Offerer''s ID number',
  `ID_recip` int(11) NOT NULL COMMENT 'Recipient''s ID number',
  `aid_date` varchar(10) NOT NULL COMMENT 'timestamp',
  `aid_money` int(11) NOT NULL,
  `aid_tech` int(11) NOT NULL,
  `aid_cap` int(11) NOT NULL,
  `aid_inf1` int(11) NOT NULL,
  `aid_inf2` int(11) NOT NULL,
  `aid_inf3` int(11) NOT NULL,
  `aid_stat` varchar(1) NOT NULL DEFAULT '0' COMMENT '0 = Offered; 1 = Accepted; 2 = Expired',
  PRIMARY KEY (`ID_aid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `military` (
  `ID` mediumint(9) NOT NULL COMMENT 'ID number',
  `inf_1` int(11) NOT NULL DEFAULT '0' COMMENT 'Infantry - Level 1',
  `inf_2` int(11) NOT NULL DEFAULT '0' COMMENT 'Infantry - Level 2',
  `inf_3` int(11) NOT NULL DEFAULT '0' COMMENT 'Infantry - Level 3',
  `inf_loss` int(11) NOT NULL DEFAULT '0' COMMENT 'Infantry Losses Total',
  `armor_1` int(11) NOT NULL DEFAULT '0' COMMENT 'Armor - Level 1',
  `armor_2` int(11) NOT NULL DEFAULT '0' COMMENT 'Armor - Level 2',
  `armor_3` int(11) NOT NULL DEFAULT '0' COMMENT 'Armor - Level 3',
  `armor_4` int(11) NOT NULL DEFAULT '0' COMMENT 'Armor - Level 4',
  `armor_5` int(11) NOT NULL DEFAULT '0' COMMENT 'Armor - Level 5',
  `armor_loss` int(11) NOT NULL DEFAULT '0' COMMENT 'Armor Losses Total',
  `call_to_arms` varchar(1) NOT NULL DEFAULT '0' COMMENT '0 = none; 1 = bonus 5% troops',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `mod_admin_post` (
  `ID_post` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Post ID number',
  `ID_mod_admin` int(11) NOT NULL COMMENT 'Poster''s ID Number',
  `page` varchar(1) NOT NULL COMMENT '0 = index; 1 = welcome; 2 = versioninfo; 3 = basicinfo; 4 = deleted',
  `title` varchar(50) NOT NULL COMMENT 'post title',
  `body` text NOT NULL COMMENT 'post body',
  `post_date` varchar(10) NOT NULL COMMENT 'post date (MM-DD-YYYY)',
  PRIMARY KEY (`ID_post`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3;

INSERT INTO `mod_admin_post` (`ID_post`, `ID_mod_admin`, `page`, `title`, `body`, `post_date`) VALUES
(1, 1, '1', 'Welcome to iC!', 'This is the iCentral page. Here you will be able to find out about new updates to the game as well as posts by myself and the moderators. You will also be able to find statistics for best nation, strongest alliance, top zone, and many more!<br />\r\n<br />\r\nMake sure you look here when possible to keep up-to-date on game events as well. Be sure to also check out the <a class=''link_inline'' href=''docs/versioninfo.php''>Version Info</a> page to see the complete list of game progress. The <a class=''link_inline'' href=''docs/basicinfo.php''>Basic Info</a> page contains general information that myself and the moderation team have deemed essential to first-time players.<br />\r\n<br />\r\nPlease be aware: Production of this version of iC has been halted. Good news, though; I have begun the final rewrite of the game! All nations in the game will not be transferred over to the beta release. Feel free to play around with it. This is only active for portfolio reasons. Production stopped just shy of two years ago and so this site is outdated with subpar coding. The beta will be clean, fresh and completely functional.<br /><br />\r\nWarfare has been disabled.<br /><br />\r\nEnjoy!', '1329246616'),
(2, 1, '0', 'Welcome to iC!', 'We are currently under construction, however if you would like to test the site, please visit our <a class=''link_inline'' href=''register.php''>registration</a> page. If you have already registered, please visit our <a class=''link_inline'' href=''login.php''>login</a> page.<br />\r\n<br />\r\nPlease be aware: Production of this version of iC has been halted. Good news, though; I have begun the final rewrite of the game! All nations in the game will not be transferred over to the beta release. Feel free to play around with it. This is only active for portfolio reasons. Production stopped just shy of two years ago and so this site is outdated with subpar coding. The beta will be clean, fresh and completely functional.<br /><br />\r\nWarfare has been disabled.', '1329246616');

CREATE TABLE IF NOT EXISTS `nations` (
  `ID` mediumint(9) NOT NULL COMMENT 'ID number',
  `nation` varchar(20) NOT NULL COMMENT 'nation name',
  `title` varchar(20) NOT NULL COMMENT 'leader title',
  `capitol` varchar(20) NOT NULL COMMENT 'capitol city name',
  `currency` smallint(2) NOT NULL COMMENT 'currency type',
  `language` smallint(2) NOT NULL COMMENT 'national language',
  `ethnicity` smallint(2) NOT NULL COMMENT 'national ethnicity',
  `creed` smallint(2) NOT NULL COMMENT 'national creed',
  `poli_sci` smallint(2) NOT NULL COMMENT 'national government',
  `zone` smallint(2) NOT NULL COMMENT 'zone (team)',
  `land_type` smallint(2) NOT NULL COMMENT 'land type',
  `region` varchar(20) NOT NULL COMMENT 'land division name',
  `tax_rate` smallint(2) NOT NULL COMMENT 'tax rate',
  `peace_war` smallint(2) NOT NULL COMMENT 'peace or war mode',
  `creation` varchar(10) NOT NULL COMMENT 'nation creation timestamp',
  `flag` mediumint(9) NOT NULL DEFAULT '0' COMMENT 'Nation Flag',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `nation` (`nation`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `nation_variables` (
  `ID` mediumint(9) NOT NULL COMMENT 'ID number',
  `history` varchar(325) NOT NULL DEFAULT 'About my nation...' COMMENT 'About Bio for nation',
  `infra` double NOT NULL DEFAULT '5' COMMENT 'infrastructure',
  `tech` double NOT NULL DEFAULT '1' COMMENT 'technology',
  `capital` double NOT NULL DEFAULT '0.5' COMMENT 'capital',
  `land` double NOT NULL DEFAULT '1' COMMENT 'land',
  `treasury` double NOT NULL DEFAULT '500000' COMMENT 'treasury total (+/-)',
  `bills` double NOT NULL COMMENT 'bills/upkeep',
  `collection` double NOT NULL COMMENT 'collection/income',
  `citizens` varchar(15) NOT NULL DEFAULT '100' COMMENT 'total citizens',
  `labor_force` varchar(15) NOT NULL COMMENT 'working citizens',
  `opinion` double NOT NULL DEFAULT '10' COMMENT 'public opinion',
  `pollution` double NOT NULL DEFAULT '7.5' COMMENT 'pollution rating',
  `last_tax` varchar(10) NOT NULL COMMENT 'Last Collection Date',
  `last_bill` varchar(10) NOT NULL COMMENT 'Last Bills Date',
  `resource_1` int(11) NOT NULL COMMENT 'Resource #1',
  `resource_2` int(11) NOT NULL COMMENT 'Resource #2',
  `nat_rate` varchar(20) NOT NULL DEFAULT '0' COMMENT 'National Rating',
  `eco_power` varchar(20) NOT NULL DEFAULT '0' COMMENT 'Economic Power',
  `mil_power` varchar(20) NOT NULL DEFAULT '0' COMMENT 'Military Power',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `private_message` (
  `ID_message` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Message ID number',
  `ID_recip` int(11) NOT NULL COMMENT 'Recipient ID number',
  `ID_send` int(11) NOT NULL COMMENT 'Sender ID number',
  `subject` varchar(25) NOT NULL COMMENT 'Message subject',
  `time_sent` varchar(10) NOT NULL COMMENT 'Message date/time',
  `body` varchar(1000) NOT NULL COMMENT 'Message body',
  `read_pm` varchar(1) NOT NULL DEFAULT '0' COMMENT '0=unread; 1=read; 2=deleted',
  `read_date` varchar(10) NOT NULL COMMENT 'Date it was read',
  PRIMARY KEY (`ID_message`),
  KEY `ID_recip` (`ID_recip`,`ID_send`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `resource_trade` (
  `ID_trade` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Trade ID number',
  `ID_offerer` int(11) NOT NULL COMMENT 'Offerer''s ID number',
  `ID_recip` int(11) NOT NULL COMMENT 'Recipient''s ID number',
  `trade_date` varchar(10) NOT NULL COMMENT 'timestamp',
  `trade_stat` varchar(1) NOT NULL DEFAULT '0' COMMENT '0=offered;1=active;2=canceled',
  PRIMARY KEY (`ID_trade`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `switchboard` (
  `ID_switch` tinyint(4) NOT NULL,
  `site_online` varchar(1) NOT NULL COMMENT '0 = Online; 1=Offline; 2=Admin Only',
  `version` varchar(12) NOT NULL COMMENT 'Version Number',
  `multiple_nations` varchar(1) NOT NULL COMMENT '0 = Allow Multi''s; 1 = No Multi''s; 2 = Admin Only Multi''s',
  `new_nations` varchar(1) NOT NULL COMMENT '0 = Allow New Nations; 1 = No New Nations',
  PRIMARY KEY (`ID_switch`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `switchboard` (`ID_switch`, `site_online`, `version`, `multiple_nations`, `new_nations`) VALUES
(1, '0', '0.5.10a', '0', '0');

CREATE TABLE IF NOT EXISTS `temp` (
  `username` varchar(20) NOT NULL COMMENT 'Username',
  `res_1` tinyint(4) NOT NULL COMMENT 'Resource 1',
  `res_2` tinyint(4) NOT NULL COMMENT 'Resource 2',
  `res_3` tinyint(4) NOT NULL COMMENT 'Resource 3',
  `res_4` tinyint(4) NOT NULL COMMENT 'Resource 4',
  `res_5` tinyint(4) NOT NULL COMMENT 'Resource 5',
  `res_6` tinyint(4) NOT NULL COMMENT 'Resource 6'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `users` (
  `ID` mediumint(9) NOT NULL AUTO_INCREMENT COMMENT 'ID number',
  `username` varchar(20) NOT NULL COMMENT 'Username',
  `password` varchar(35) NOT NULL COMMENT 'Password',
  `email` varchar(60) NOT NULL COMMENT 'email address',
  `ToS` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Terms of Service',
  `IP` varchar(20) NOT NULL COMMENT 'IP Address',
  `IP_block` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Allow Multiple Nations?',
  `nat_exist` varchar(1) NOT NULL DEFAULT '0' COMMENT '0 = no nation; 1 = nation exists; 2 = nation temp-banned; 3 = nation deleted',
  `mod_admin` int(1) NOT NULL DEFAULT '0' COMMENT '0 = Player; 1 = Mod; 2 = Admin',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `warfare` (
  `ID_war` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'War ID number',
  `ID_attack` int(11) NOT NULL COMMENT 'Attacker''s ID number',
  `ID_defend` int(11) NOT NULL COMMENT 'Defender''s ID number',
  `war_date` varchar(10) NOT NULL COMMENT 'timestamp',
  `war_stat` varchar(1) NOT NULL DEFAULT '0' COMMENT '0 = active; 1 = atk off peace; 2 = def off peace; 3 = expired; 4 = defacto ceasefire; 5 = defacto peace',
  `att_inf_cas` int(11) NOT NULL DEFAULT '0' COMMENT 'Attacker''s Infantry Casualties',
  `def_inf_cas` int(11) NOT NULL DEFAULT '0' COMMENT 'Defender''s Infantry Casualties',
  `att_armor_cas` int(11) NOT NULL DEFAULT '0' COMMENT 'Attacker''s Armor Casualties',
  `def_armor_cas` int(11) NOT NULL DEFAULT '0' COMMENT 'Defender''s Armor Casualties',
  `open_shot` varchar(1) NOT NULL DEFAULT '0' COMMENT '0 = none; 1 = happened',
  `last_combat` varchar(10) NOT NULL COMMENT 'Date of the Last Battle',
  PRIMARY KEY (`ID_war`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
