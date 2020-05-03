<?php

class DB extends SQLite3
{
    function __construct()
    {
        $this->open('test.db');
    }
}

$db  = new DB();
if (!$db) {
    echo $db->lastErrorMessage();
} else {
    echo 'Opened db successfully';
}

$sql = <<<EOF
    CREATE TABLE IF NOT EXISTS COMPANY(
    ID INTEGER PRIMARY KEY AUTOINCREMENT,
    NAME           TEXT      NOT NULL,
    AGE            INT       NOT NULL,
    ADDRESS        CHAR(50),
    SALARY         REAL
 );
EOF;

try {
    $db->exec($sql);
} catch (Exception $e) {
    echo $e->getMessage();
}

$id = mt_rand(2, 10);

$sql2 = "INSERT INTO COMPANY (NAME, ADDRESS, SALARY, AGE) VALUES ('Foo', 'Bar',2000, 20)";

try {
    $res = $db->exec($sql2);
    var_dump($res);
} catch (Exception $e) {
    echo $e->getMessage();
}

$sql3 = "SELECT * FROM COMPANY";
try {
    $ret = $db->query($sql3);
    while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
        echo "ID = " . $row['ID'] . "\n";
        echo "NAME = " . $row['NAME'] . "\n";
        echo "ADDRESS = " . $row['ADDRESS'] . "\n";
        echo "SALARY = " . $row['SALARY'] . "\n\n";
    }
    echo "Operation done successfully\n";
    $db->close();
} catch (Exception $e) {
    echo $e->getMessage();
}

$locale = Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);

echo $locale;

