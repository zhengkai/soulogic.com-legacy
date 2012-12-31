<?php
// define('TANGO_DEV', 'Freya'); // 仅限开发时打开

DBz::setServer(
	"Blog",
	array(
		"user"     => "soulogic",
		"password" => "qwHoSB1SDB3OH",
		"database" => "soulogic",
		"unix_socket" => "/var/run/mysqld/mysqld.sock",
	)
);

DBz::setServer(
	"PDA",
	array(
		"user"     => "soulogic",
		"password" => "qwHoSB1SDB3OH",
		"database" => "soulogic_pda",
		"unix_socket" => "/var/run/mysqld/mysqld.sock",
	)
);

MCz::setServer();

// 不要使用默认的，去掉注释时重新生成个
// 可使用 pwgen -cns 70 1 来生成

// define("COMMENT_TOKEN_SALT", "yHoB7vhNRRWzcsbiGbheYh7g9wl8gDChylBGYZ6q1aimeGUtsGvtHSTtnnbmz8Gj7wnP1nrE5pe");
