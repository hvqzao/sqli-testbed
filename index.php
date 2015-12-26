<?php

/* Install

mysql -u root -p
(if you dont remember mysql root password - in Debian-based (i.e. Ubuntu, Kali) systems you could use "dpkg-reconfigure mysql-server-5.5" to change it)

    create database if not exists sqli;
    use sqli;
    create table accounts (id MEDIUMINT NOT NULL AUTO_INCREMENT, username VARCHAR(32) NOT NULL, password VARCHAR(32), primary key (id));
    insert into accounts (username, password) values ('admin','e3274be5c857fb42ab72d786e281b4b8');
    insert into accounts (username, password) values ('site','21a0bcfc0dc6641a3f5297fadddffed2');
    create table finance (id MEDIUMINT NOT NULL AUTO_INCREMENT, department VARCHAR(32) NOT NULL, balance numeric(15,2), primary key (id));
    insert into finance (department, balance) values ('marketing',1200000);
    insert into finance (department, balance) values ('sales',450000);
    insert into finance (department, balance) values ('it',960000);
    insert into finance (department, balance) values ('logistics',720000);
    use mysql;
    -- insert into user (host, user, password, select_priv, insert_priv, update_priv) VALUES ('localhost', 'sqli', PASSWORD('zoacUtOvee'), 'Y', 'Y', 'Y');
    -- grant select,insert,update,delete,create,drop on sqli.* to 'sqli'@'localhost' identified by 'zoacUtOvee';
    insert into user (host, user, password, select_priv) VALUES ('localhost', 'sqli', PASSWORD('zoacUtOvee'), 'Y');
    flush privileges;

/var/www/html/sqli/.htaccess

    Order Allow,Deny
    Allow from 127.

*/

$conn = mysql_connect("127.0.0.1", "sqli", "zoacUtOvee") or die("Error: Unable to connect to MySQL");
$db = mysql_select_db("sqli",$conn) or die("Error: Database selection has failed");
$debug_id = function($where) { global $debug, $id; if ($debug) { echo "<pre>\nid: \"$id\" ($where)\n</pre>\n"; }};
$show_query = function($type) { global $show, $query; if ($show) { echo "<pre>\nquery: \"$query\" ($type)\n</pre>\n"; }};
$debug_param = function($data) { global $debug; if ($debug) { echo "<pre>\nparam: "; print_r($data); echo "</pre>\n"; }};
$verbose_drop = function($text) { global $verbose; if ($verbose) { echo "<pre>\ndrop: $text</pre>\n"; }};
$verbose_filter = function($text) { global $verbose; if ($verbose) { echo "<pre>\nfilter: $text</pre>\n"; }};

// Params

if (isset($_GET['id'])) $id = $_GET['id']; else $id = '';
if (isset($_GET['dept'])) $dept = mysql_real_escape_string($_GET['dept']); else $dept = '';
//$type = 'union';
$type = 'boolean';
if (isset($_GET['u'])) { $id = $_GET['u']; $type = 'union'; }
if (isset($_GET['b'])) { $id = $_GET['b']; $type = 'boolean'; }

// Setup

//$verbose = true; // filters verbosity
//$show = true; // query verbosity
//$debug = true; // debugging verbosity

// Drop

//$verbose_drop('no quotes'); if (preg_match('/[\'"]/', $id)) exit('attack');
//$verbose_drop('no whitespaces'); if (preg_match('/\s/', $id)) exit('attack');
//$verbose_drop('no slashes'); if (preg_match('/[\/\\\\]/', $id)) exit('attack');

//$verbose_drop('no sql misc keywords'); if (preg_match('/(AND|NULL|WHERE|LIMIT)/', $id)) exit('attack');
//$verbose_drop('no sql misc keywords (case insentitive)'); if (preg_match('/(and|null|where|limit)/i', $id)) exit('attack');

//$verbose_drop('no sql boolean keywords'); if (preg_match('/(AND|OR|NULL|NOT)/', $id)) exit('attack');
//$verbose_drop('no sql boolean keywords (case insentitive)'); if (preg_match('/(and|or|null|not)/i', $id)) exit('attack');

//$verbose_drop('no sql union keywords'); if (preg_match('/(UNION|SELECT|FROM|WHERE)/', $id)) exit('attack');
//$verbose_drop('no sql union keywords (case insentitive)'); if (preg_match('/(union|select|from|where)/i', $id)) exit('attack');

//$verbose_drop('no sql where keywords'); if (preg_match('/(GROUP|ORDER|HAVING|LIMIT)/', $id)) exit('attack');
//$verbose_drop('no sql where keywords (case insentitive)'); if (preg_match('/(group|order|having|limit)/i', $id)) exit('attack');

//$verbose_drop('no sql operators'); if (preg_match('/(INTO|FILE|CASE)/i', $id)) exit('attack');
//$verbose_drop('no sql operators (case insentitive)'); if (preg_match('/(into|file|case)/i', $id)) exit('attack');

//$verbose_drop('no sql comments (# allowed)'); if (preg_match('/(--|\/\*)/', $id)) exit('attack');
//$verbose_drop('no sql comments (-- allowed)'); if (preg_match('/(#|\/\*)/', $id)) exit('attack');
//$verbose_drop('no sql comments'); if (preg_match('/(--|#|\/\*)/', $id)) exit('attack');

//$verbose_drop('no boolean operators'); if (preg_match('/(=|&|\|)/', $id)) exit('attack');

// Filter

//$verbose_filter('length limit'); $id = substr($id, 0, 140);

//$verbose_filter('remove OR'); $id = preg_replace("/OR/","",$id);					
//$verbose_filter('remove AND (case insensitive)'); $id = preg_replace("/and/i","",$id);

//$verbose_filter('remove quotes'); $id = preg_replace('/[\'"]/i',"",$id);					
//$verbose_filter('remove whitespaces'); $id = preg_replace('/\s/i',"",$id);					
//$verbose_filter('remove slashes'); $id = preg_replace('/[\/\\\\]/i',"",$id);					

//$verbose_filter('remove sql misc keywords'); $id = preg_replace('/(AND|NULL|WHERE|LIMIT)/',"",$id);					
//$verbose_filter('remove sql misc keywords (case insensitive)'); $id = preg_replace('/(and|null|where|limit)/i',"",$id);					

//$verbose_filter('remove sql boolean keywords'); $id = preg_replace('/(AND|OR|NULL|NOT)/',"",$id);					
//$verbose_filter('remove sql boolean keywords (case insensitive)'); $id = preg_replace('/(and|or|null|not)/i',"",$id);					

//$verbose_filter('remove sql union keywords'); $id = preg_replace('/(UNION|SELECT|FROM|WHERE)/',"",$id);					
//$verbose_filter('remove sql union keywords (case insensitive)'); $id = preg_replace('/(union|select|from|where)/i',"",$id);					

//$verbose_filter('remove sql where keywords'); $id = preg_replace('/(GROUP|ORDER|HAVING|LIMIT)/',"",$id);					
//$verbose_filter('remove sql where keywords (case insensitive)'); $id = preg_replace('/(group|order|having|limit)/i',"",$id);					

//$debug_id('ips');

// Query

switch ($type):

    case 'union':

        $query = "select balance from finance where id='$id' or department='$dept'";
        $show_query($type);
        if($data = @mysql_fetch_array(mysql_query($query))) {
            $debug_param($data);
            echo "balance: $ ${data['balance']}";
        } else {
            echo "no results";
        }
        break;

    case 'boolean':

        $query = "select max(balance) from finance where id='$id' or department='$dept'";
        $show_query($type);
        if($data = @mysql_fetch_array(mysql_query($query))) {
            $debug_param($data);
            echo "balance: $ ${data[0]}";
        } else {
            echo "no results";
        }
        break;


    // default:
endswitch;
?>
