<?

include(__DIR__ . '/ini.php');

$filename = __DIR__ . "/db_backups/backup_".date("m_d_y_B").".sql";

$db_dump_string = "mysqldump -u [".DB_USER_NAME."] -p[".DB_PASSWORD."] --socket=[".DB_LOCATION."] db96975_think_tank_new   > $filename";

echo $db_dump_string;

echo shell_exec($db_dump_string);




?>