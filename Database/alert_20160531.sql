
--已更新20160622

CREATE TABLE IF NOT EXISTS `tb_weichat_bind` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weichat_id` int(11) NOT NULL COMMENT '微信ID',
  `phone` varchar(20) NOT NULL COMMENT '手机号',
  `usage_type` tinyint(4) NOT NULL COMMENT '关联用户类型：21-家长、31-园长',
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '删除状态：0-未删除，1-删除',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `weichat_id`(`weichat_id`),
  INDEX `phone`(`phone`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='微信用户绑定表';

ALTER TABLE  `tb_student_parent` ADD  `relation_title` VARCHAR( 255 ) NULL COMMENT '关系名称' AFTER  `relation_id` ;

------------------------

--已更新20160630

CREATE TABLE IF NOT EXISTS `tb_bindparent_apply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weichat_id` int(11) NOT NULL COMMENT '微信ID',
  `student_id` int(11) NOT NULL COMMENT '学生ID',
  `phone` varchar(20) NOT NULL COMMENT '手机号',
  `relation_id` tinyint(4) NOT NULL COMMENT '关系类型',
  `relation_title` varchar(255) NOT NULL COMMENT '关系称谓',
  `bind_key` varchar(255) NOT NULL COMMENT '绑定KEY值',
  `status` tinyint(4) NOT NULL COMMENT '状态：0-未处理、1-已同意、2-已拒绝',
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '删除状态：0-未删除，1-删除',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `phone`(`phone`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='家长绑定申请表';

--------------------------

--已更新20160706

ALTER TABLE  `tb_weichat_bind` CHANGE  `usage_type`  `usage_type` TINYINT( 4 ) NOT NULL COMMENT '关联用户类型：21-家长、31-园长、32-老师、33-保健医生、34-后勤人员';

ALTER TABLE  `tb_student_detection` ADD  `temperature_threshold` FLOAT( 3, 1 ) NULL COMMENT  '体温阈值' AFTER  `raw_temperature` ;

----------------

----------20170710学校职工重新整理（已更新测试20160715，20160719更新正式）
-- tb_school_headmaster表将删除
-- tb_school_teacher表将删除
-- tb_school_doctor表将删除
-- tb_school_supporter表将删除
-- tb_sign_record表删除

DROP TABLE tb_school_headmaster;
DROP TABLE tb_school_teacher;
DROP TABLE tb_school_doctor;
DROP TABLE tb_school_supporter;
DROP TABLE tb_sign_record;


CREATE TABLE IF NOT EXISTS `tb_staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` tinyint(4) NOT NULL COMMENT '职工类型：31-园长、32-老师、33-保健医生、34-后勤人员',
  `school_id` int(11) NOT NULL COMMENT '学校ID',
  `name` varchar(255) DEFAULT NULL COMMENT '姓名',
  `phone` varchar(20) NOT NULL COMMENT '手机号码',
  `rfid` varchar(255) DEFAULT NULL COMMENT 'RFID',
  `qrcode_url` varchar(500) NOT NULL DEFAULT '' COMMENT '二维码',
  `qrcode_school_key` varchar(255) NOT NULL DEFAULT '' COMMENT '二维码中所使用的学校KEY',
  `sign_type_id` int(11) NOT NULL COMMENT '签到类型ID，等于0时表示不用签到',
  `status` tinyint(4) NOT NULL COMMENT '状态：0-禁用、1-有效',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='学校职工表';


CREATE TABLE IF NOT EXISTS `tb_staff_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `school_id` int(11) NOT NULL COMMENT '学校ID',
  `class_id` int(11) NOT NULL COMMENT '班级ID',
  `staff_id` int(11) NOT NULL COMMENT '职工ID',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='学校职工与班级关联表';


CREATE TABLE IF NOT EXISTS `tb_sign_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(255) NOT NULL COMMENT '类型名称',
  `in_time` time NOT NULL COMMENT '签到时间',
  `out_time` time NOT NULL COMMENT '签退时间',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='签到类型表';


CREATE TABLE IF NOT EXISTS `tb_staff_signdate` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL COMMENT '职工ID',
  `sign_timestamp` bigint(20) NOT NULL COMMENT '日期时间戳（用于查询、计算）',
  `sign_date` date NOT NULL COMMENT '日期（只用于提示，不用于查询、计算）',
  `sign_status` tinyint(4) NOT NULL COMMENT '考勤状态：1-缺勤、2-正常签到且未签退、3-迟到且未签退、4-正常签到且正常签退、5-正常签到且早退、6-迟到且正常签退、7-迟到且早退',
  `set_intime` time NOT NULL COMMENT '当天设定的签到时间',
  `set_outtime` time NOT NULL COMMENT '当天设定的签退时间',
  `in_time` time DEFAULT NULL COMMENT '签到时间',
  `out_time` time DEFAULT NULL COMMENT '签退时间',
  `in_img` varchar(500) NOT NULL DEFAULT '' COMMENT '签到图片地址',
  `out_img` varchar(500) NOT NULL DEFAULT '' COMMENT '签退图片地址',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`),
  INDEX `staff_date` (`staff_id`, `sign_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='职工签到状态表';


CREATE TABLE IF NOT EXISTS `tb_staff_signrecord` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL COMMENT '职工ID',
  `sign_timestamp` bigint(20) NOT NULL COMMENT '日期时间戳（用于查询、计算）',
  `sign_date` date NOT NULL COMMENT '日期（只用于提示，不用于查询、计算）',
  `first` tinyint(4) NOT NULL COMMENT '是否是当天第一条：0-否、1-是',
  `lastest` tinyint(4) NOT NULL COMMENT '是否是当天最后一条：0-否、1-是',
  `img` varchar(500) NOT NULL DEFAULT '' COMMENT '图片地址',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`),
  INDEX `staff_date` (`staff_id`, `sign_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='职工签到记录表';

------------
