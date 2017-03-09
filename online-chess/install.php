<?php
include('db_config.inc.php');

$q = "CREATE TABLE registered_users (
  username varchar(255) NOT NULL UNIQUE default '0',
  password varchar(255) NOT NULL, 
  email_id varchar(255) NOT NULL UNIQUE,
  online_status TINYINT(1) UNSIGNED NOT NULL default 0,
  PRIMARY KEY(email_id))ENGINE=MyISAM";
mysql_query($q) or die(mysql_error());	

$q = "CREATE TABLE membership (
  id INT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  username varchar(255) NOT NULL UNIQUE default '0',
  PRIMARY KEY(id))ENGINE=MyISAM";
mysql_query($q) or die(mysql_error());	

$q = "CREATE TABLE chess_table (
  username varchar(255) NOT NULL default '',
  host varchar(24) NOT NULL default '0',
  color TINYINT(1) UNSIGNED NOT NULL default 0,
  timestamp INT(20) UNSIGNED NOT NULL default 0,
  INDEX(username,host))ENGINE=MyISAM";
mysql_query($q) or die(mysql_error());

$q = "CREATE TABLE chess_stats (
  username varchar(255) NOT NULL default '',
  win INT(20) UNSIGNED NOT NULL default 0,
  loss INT(20) UNSIGNED NOT NULL default 0,
  draw INT(20) UNSIGNED NOT NULL default 0)ENGINE=MyISAM";
mysql_query($q) or die(mysql_error());	

$q = "CREATE TABLE ongoing_games (
  username varchar(255) NOT NULL default '',
  opponents varchar(24) DEFAULT NULL,
  gameid varchar(255) NOT NULL DEFAULT '0',
  host varchar(24) DEFAULT NULL,
  white varchar(24) DEFAULT NULL,
  urturn TINYINT(1) UNSIGNED DEFAULT NULL,
  INDEX(gameid))ENGINE=MyISAM";
mysql_query($q) or die(mysql_error());	

$q = "CREATE TABLE offer_draw (
  offered_by varchar(24) NOT NULL default '0',
  gameid varchar(255) NOT NULL default '0',
  INDEX(gameid))ENGINE=MyISAM";
mysql_query($q) or die(mysql_error());

$q = "CREATE TABLE pgn (
  username varchar(255) DEFAULT NULL,
  opponents varchar(255) DEFAULT NULL,
  hd text default NULL,
  mv longtext default NULL,
  gameid varchar(255) NOT NULL default '',
  INDEX(gameid))ENGINE=MyISAM";
mysql_query($q) or die(mysql_error());

header("location: ./index.php");
?>
