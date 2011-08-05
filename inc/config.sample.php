<?php
Page::setBeginTime();

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

define("COMMENT_TOKEN_SALT", "yHoB7vhNRRWzcsbiGbheYh7g9wl8gDChylBGYZ6q1aimeGUtsGvtHSTtnnbmz8Gj7wnP1nrE5pe");

