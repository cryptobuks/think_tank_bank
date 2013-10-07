<?

include(__DIR__ . '/ini.php');

$filename = __DIR__ . "/db_backups/backup_".date("m_d_y_B").".sql";

$db_dump_string = "mysqldump --add-drop-table -h ".DB_LOCATION." -u ".DB_USER_NAME." -p".DB_PASSWORD." db96975_think_tank_new  > $filename";




/* insert DB stuff
mysql -u db96975_jimmy -pkgG206PV3q82N23 -h internal-db.s96975.gridserver.com db96975_think_tank_old < /nfs/c06/h06/mnt/96975/domains/wonkbook.io/html/db_backups/backup_10_07_13_909.sql

*/

echo $db_dump_string;

echo shell_exec($db_dump_string);




?>