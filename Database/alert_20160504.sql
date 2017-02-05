--已更新正式

ALTER TABLE  `tb_school_parent` ADD  `frid` VARCHAR( 255 ) NOT NULL DEFAULT  '' COMMENT  'FRID' AFTER  `type` ;

ALTER TABLE  `tb_school_parent` ADD  `qrcode_url` VARCHAR( 500 ) NOT NULL DEFAULT  '' COMMENT  '接小孩二维码' AFTER  `frid` ;




ALTER TABLE  `tb_school` ADD  `key` VARCHAR( 255 ) NULL COMMENT  '正在使用的KEY' AFTER  `phone` ;

ALTER TABLE  `tb_school` ADD  `key_new` VARCHAR( 255 ) NULL COMMENT  '新生成的KEY' AFTER  `key` ;

ALTER TABLE  `tb_school` ADD  `key_rel_status` TINYINT( 4 ) NOT NULL DEFAULT  '0' COMMENT  '与正在使用的KEY相关联项（家长二维码）更新标志：0-未更新、1-更新中、2-部分更新、3-已全部更新' AFTER  `key_new` ;

ALTER TABLE  `tb_student` ADD  `away_img` VARCHAR( 500 ) NOT NULL DEFAULT  '' COMMENT  '学生上次被接走时的图片' AFTER  `birthday` ;

ALTER TABLE  `tb_student` ADD  `away_time` DATETIME NULL COMMENT  '学生上次被接走的时间' AFTER  `away_img` ;

ALTER TABLE  `tb_school_parent` ADD  `qrcode_school_key` VARCHAR( 255 ) NOT NULL DEFAULT  '' COMMENT  '二维码中所使用的学校KEY' AFTER  `qrcode_url` ;

ALTER TABLE  `tb_student` ADD  `away_parent_id` INT NOT NULL DEFAULT  '0' COMMENT  '上次接走学生的家长ID' AFTER  `birthday` ;

ALTER TABLE  `tb_school` ADD  `key_new_time` DATETIME NULL COMMENT  '新KEY值生成的时间' AFTER  `status` ;
UPDATE  `tb_school` SET  `key_new_time` =  `created`;

CREATE TABLE `tb_student_away_record` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `student_id` int(10) NOT NULL COMMENT '学生ID',
  `parent_id` int(10) NOT NULL COMMENT '家长ID',
  `img` varchar(500) NOT NULL COMMENT '接小孩图片',
  `created` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='接学生记录流水';




ALTER TABLE  `tb_school` DROP  `key_rel_status` ;

ALTER TABLE  `tb_school_parent` CHANGE  `frid`  `rfid` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  '' COMMENT  'RFID';

ALTER TABLE `tb_school_parent` DROP `qrcode_url`;

ALTER TABLE  `tb_school_parent` DROP  `qrcode_school_key` ;

ALTER TABLE  `tb_student` DROP  `away_parent_id` ;

ALTER TABLE  `tb_student` DROP  `away_img` ;

ALTER TABLE  `tb_student` DROP  `away_time` ;

ALTER TABLE  `tb_student_away_record` ADD INDEX (  `student_id` ,  `created` ) ;

ALTER TABLE  `tb_student_parent` ADD  `qrcode_url` VARCHAR( 500 ) NOT NULL DEFAULT  '' COMMENT  '接小孩二维码' AFTER  `relation_id` ;

ALTER TABLE  `tb_student_parent` ADD  `qrcode_school_key` VARCHAR( 255 ) NOT NULL DEFAULT  '' COMMENT  '二维码中所使用的学校KEY' AFTER  `qrcode_url` ;

ALTER TABLE  `tb_school` ADD  `key_active_time` DATETIME NULL COMMENT  'KEY值生效时间' AFTER  `key_new_time` ;



--已更新20160614

ALTER TABLE  `tb_student_parent` ADD  `rfid` VARCHAR( 255 ) NULL COMMENT  'RFID' AFTER  `parent_id` ;

ALTER TABLE  `tb_student_detection` ADD  `raw_temperature` FLOAT( 3, 1 ) NULL COMMENT  '原始温度' AFTER  `env_temperature` ;


--已更新20160614

ALTER TABLE `tb_school_parent` DROP `rfid`;


