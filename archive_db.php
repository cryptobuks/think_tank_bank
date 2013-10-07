<?

include(__DIR__ . '/ini.php');

$filename = __DIR__ . "/db_backups/backup_".date("m_d_y_B").".sql";

$db_dump_string = "mysqldump -u root -p[".DB_LOCATION."] [".DB_USER_NAME."] > $filename";

echo $db_dump_string;

echo shell_exec($db_dump_string);




?>