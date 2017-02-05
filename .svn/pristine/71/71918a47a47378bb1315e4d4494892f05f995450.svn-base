--已更新正式

ALTER TABLE  `tb_student_detection` ADD  `env_temperature` FLOAT( 3, 1 ) NULL COMMENT  '环境温度' AFTER  `temperature` ;

ALTER TABLE  `tb_school_parent` ADD INDEX (  `phone` ) ;
ALTER TABLE  `tb_parent` ADD INDEX (  `phone` ) ;

ALTER TABLE  `tb_student` ADD INDEX `name_birthday` (  `name`,  `birthday`) ;

ALTER TABLE  `tb_parent` ENGINE = INNODB;