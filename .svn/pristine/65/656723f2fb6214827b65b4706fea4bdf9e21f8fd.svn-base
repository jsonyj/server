--20160721未更新（已更新测试20160809,已更新正式20160813(X), 已更新正式20160817）

ALTER TABLE  `tb_student_detection` ADD  `terminal_img_id` VARCHAR( 255 ) NOT NULL DEFAULT  '' COMMENT  '终端上传的img_id' AFTER  `lastest` ;

ALTER TABLE  `tb_student_detection` ADD  `file_img_id` INT NOT NULL DEFAULT  '0' COMMENT  '保存的缩略图文件id' AFTER  `terminal_img_id` ;

ALTER TABLE  `tb_student_detection` ADD  `org_img_url` VARCHAR( 500 ) NOT NULL DEFAULT  '' COMMENT  '原始图片url' AFTER  `file_img_id` ;

ALTER TABLE  `tb_student_detection` ADD  `status` TINYINT NOT NULL DEFAULT  '1' COMMENT  '状态：1-正常、0-退回' AFTER  `org_img_url` ;


CREATE TABLE IF NOT EXISTS `tb_detection_claim` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `detection_id` int(11) NOT NULL COMMENT '检测数据ID',
  `terminal_img_id` varchar(255) NOT NULL COMMENT '终端图片标识ID',
  `student_id` int(11) NOT NULL COMMENT '学生ID',
  `type` tinyint(4) NOT NULL COMMENT '类型：0-退回、1-认领',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态：0-无效、1-有效',
  `op_uid` int(11) NOT NULL COMMENT '操作人员ID',
  `op_utype` tinyint(4) NOT NULL COMMENT '操作人员类型：11-学校管理员、21-家长、32-老师',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='检测数据认领表';


---------


--20160725未更新（已更新测试20160809,已更新正式20160813(X), 已更新正式20160817）

CREATE TABLE IF NOT EXISTS `tb_student_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `student_id` int(11) NOT NULL COMMENT '学生ID',
  `content` text NOT NULL COMMENT '内容',
  `url` varchar(500) NOT NULL COMMENT '跳转url',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态：0-未读、1-已读',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='学生消息列表';


CREATE TABLE IF NOT EXISTS `tb_application` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT '应用名称',
  `access_key` varchar(255) NOT NULL COMMENT '授权key值',
  `aes_key` varchar(255) NOT NULL COMMENT 'aes加密key值',
  `aes_iv` varchar(255) NOT NULL COMMENT 'aes加密向量',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态：0-禁用、1-正常',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='应用表';


ALTER TABLE  `tb_sign_type` ADD  `school_id` INT( 11 ) NOT NULL COMMENT  '学校ID' AFTER  `id` ;


ALTER TABLE  `tb_student_detection` ADD  `weichat_num` INT NOT NULL DEFAULT  '0' COMMENT  '主动推送次数' AFTER  `org_img_url` ;

-----------

