-- 20160825未更新（2016-8-30测试服务器更新, 20160831正式服务器更新）

CREATE TABLE IF NOT EXISTS `tb_system_notice` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `receive_uid` int(11) NOT NULL COMMENT '接收用户ID',
  `receive_utype` tinyint(4) NOT NULL COMMENT '接收用户类型：21-家长、30-职工',
  `send_uid` int(11) NOT NULL COMMENT '产生该消息的用户ID',
  `send_utype` tinyint(4) NOT NULL COMMENT '产生该消息的用户类型：21-家长、30-职工、90-应用',
  `send_user_avatar` varchar(250) NOT NULL COMMENT '产生该消息的用户头像',
  `send_user_name` varchar(250) NOT NULL COMMENT '产生该消息的用户名称',
  `is_read` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否已读：0-否、1-是',
  `is_url` tinyint(4) NOT NULL COMMENT '是否需要跳转url:0-否、1-是',
  `url` varchar(500) NOT NULL DEFAULT '' COMMENT '跳转url地址',
  `content` text NOT NULL COMMENT '通知内容',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统通知表';


CREATE TABLE IF NOT EXISTS `tb_class_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `school_id` int(11) NOT NULL COMMENT '学校ID',
  `class_id` int(11) NOT NULL COMMENT '班级ID',
  `staff_id` tinyint(4) NOT NULL COMMENT '老师或园长ID',
  `content` text NOT NULL COMMENT '内容',
  `can_reply` tinyint(4) NOT NULL COMMENT '是否可以回复:0-否、1-是',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='班级通知表';


CREATE TABLE IF NOT EXISTS `tb_class_notice_reply` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `class_notice_id` int(11) NOT NULL DEFAULT '0' COMMENT '班级通知ID',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父回复ID',
  `reply_uid` int(11) NOT NULL COMMENT '回复者ID',
  `reply_utype` int(11) NOT NULL COMMENT '回复者类型：21-家长、30-职工',
  `reply_user_avatar` varchar(250) NOT NULL COMMENT '回复者该消息的用户头像',
  `reply_user_name` varchar(250) NOT NULL COMMENT '回复者该消息的用户名称',
  `content` text NOT NULL COMMENT '回复内容',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='班级通知回复表';


CREATE TABLE IF NOT EXISTS `tb_student_praise` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `student_ids` varchar(255) NOT NULL DEFAULT '' COMMENT '接受消息学生ID',
  `staff_id` int(11) NOT NULL COMMENT '表扬老师ID',
  `content` text NOT NULL COMMENT '内容',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='小红花表扬表';


CREATE TABLE IF NOT EXISTS `tb_student_pic` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `student_id` int(11) NOT NULL COMMENT '学生ID',
  `type` tinyint(4) NOT NULL COMMENT '类型：32-老师发布的照片',
  `pic_ids` varchar(500) NOT NULL COMMENT '图片列表ID',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='学生照片表';

ALTER TABLE  `tb_student_pic` ADD `staff_id` INT NULL COMMENT  '老师id' AFTER  `student_id` ;

ALTER TABLE  `tb_class_notice` ADD `student_ids`  VARCHAR( 255 ) NOT NULL DEFAULT  '' COMMENT  '接受消息学生ID' AFTER `staff_id` ;
ALTER TABLE  `tb_class_notice` ADD `type_c` int(11) NOT NULL COMMENT '内容类型: 1、文字 2、语音' AFTER `staff_id` ;
ALTER TABLE  `tb_class_notice` ADD `voice_url`  VARCHAR( 255 ) NOT NULL DEFAULT  '' COMMENT  '语音地址' AFTER `content` ;
ALTER TABLE  `tb_class_notice` ADD `notice_type` int(11) NOT NULL COMMENT '消息类型: 1、班级通知 2、老师评价' AFTER `staff_id` ;

ALTER TABLE  `tb_class_notice_reply` ADD `branch_id` INT NULL COMMENT  '回复分支ID' AFTER  `parent_id` ;

ALTER TABLE  `tb_student_detection` ADD `state_type` INT NULL COMMENT  '检测信息状态：1-红色、2-黄色、3-绿色' AFTER  `temperature` ;


--------------------------

--------------2016-9-8已添加到正式服务器-------
ALTER TABLE  `tb_class_notice` ADD `voice_id`  int(11) NOT NULL  COMMENT '语音id' AFTER `content`;
ALTER TABLE  `tb_class_notice_reply` ADD `voice_id`  int(11) NOT NULL  COMMENT '语音id' AFTER `content` ;


------2016-9-2  (2016-9-9）（更新正式)

CREATE TABLE IF NOT EXISTS `tb_voice` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `voice_name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名称',
  `voice_path`varchar(255) NOT NULL DEFAULT '' COMMENT '文件存放路径',
  `usage_id` int(11) NOT NULL COMMENT '关联ID',
  `voice_type` int(11) NOT NULL COMMENT '文件类型',
  `data` text NOT NULL COMMENT '序列化其他数据',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='音频文件表';


ALTER TABLE  `tb_class_notice_reply` ADD `comment_type` int(11) NOT NULL COMMENT '内容类型: 1、文字 2、语音' AFTER `reply_user_name` ;

ALTER TABLE `tb_class_notice` CHANGE `notice_type` `notice_type` INT( 11 ) NOT NULL COMMENT '消息类型: 1、班级通知 2、老师评价 3、园长通知'
ALTER TABLE `tb_class_notice` CHANGE `student_ids` `student_ids` VARCHAR( 500 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '接受消息学生ID'

-- 添加检测信息状态

-- 20160907((2016-9-9）（更新正式))

ALTER TABLE  `tb_student` ADD  `device_registered` TINYINT( 4 ) NOT NULL DEFAULT  '0' COMMENT  '终端注册状态：0-未注册、1-已注册' AFTER  `birthday` ;

-- 20160909单条更新正式
ALTER TABLE  `tb_student_detection` ADD `decognition_type` int(11) NOT NULL COMMENT  '识别类型：' AFTER  `state_type` ;

ALTER TABLE `tb_student_detection` CHANGE `decognition_type` `recognition_type` INT( 11 ) NOT NULL COMMENT '识别类型：'

----------认领数据表---

ALTER TABLE  `tb_detection_claim` ADD `whether_claim` int(11) NOT NULL COMMENT  '识别类型：' AFTER  `type` ;


------------------以上(2016-9-9）（更新正式)


ALTER TABLE  `tb_class_notice_reply` ADD `student_id` int(11) NOT NULL COMMENT  '学生ID' AFTER  `class_notice_id` ;