-- turn into Symfony console command
SET foreign_key_checks = 0;
TRUNCATE `trackway`.`absences`;
TRUNCATE `trackway`.`invitations`;
TRUNCATE `trackway`.`memberships`;
TRUNCATE `trackway`.`projects`;
TRUNCATE `trackway`.`tasks`;
TRUNCATE `trackway`.`timeentries`;
TRUNCATE `trackway`.`teams`;
TRUNCATE `trackway`.`users`;
SET foreign_key_checks = 1;