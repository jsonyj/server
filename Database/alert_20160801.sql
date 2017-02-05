--20160801未更新（已更新测试20160809,已更新正式20160813(X), 已更新正式20160817）

CREATE TABLE IF NOT EXISTS `tb_help` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(255) DEFAULT NULL COMMENT '文档标题',
  `body` text COMMENT '内容',
  `status` tinyint(4) NOT NULL COMMENT '状态:0-禁用、1-有效',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='帮助文档表';


CREATE TABLE IF NOT EXISTS `tb_dream` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) DEFAULT NULL COMMENT '角色名称',
  `img_url` varchar(500) DEFAULT NULL COMMENT '角色图片',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='学生梦想表';


ALTER TABLE  `tb_student` ADD  `nickname` VARCHAR( 255 ) NOT NULL DEFAULT  '' COMMENT  '小名' AFTER  `name` ;

ALTER TABLE  `tb_student` ADD  `hobby` VARCHAR( 255 ) NOT NULL DEFAULT  '' COMMENT  '爱好' AFTER  `nickname` ;

ALTER TABLE  `tb_student` ADD  `dream_id` INT NOT NULL DEFAULT  '0' COMMENT  '梦想ID' AFTER  `hobby` ;

ALTER TABLE  `tb_student` ADD  `comment` VARCHAR( 500 ) NOT NULL DEFAULT  '' COMMENT  '老师评语' AFTER  `dream_id` ;


DROP TABLE tb_student_message;
CREATE TABLE IF NOT EXISTS `tb_user_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父ID',
  `t_uid` int(11) NOT NULL COMMENT '接收消息用户ID',
  `t_utype` tinyint(4) NOT NULL COMMENT '接收消息用户类型：21-家长、30-职工、40-学生',
  `f_uid` int(11) NOT NULL COMMENT '发送消息用户ID或应用ID',
  `f_utype` tinyint(4) NOT NULL COMMENT '发送消息用户类型：0-系统、21-家长、30-职工、90-应用',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '消息类型:0-系统消息',
  `is_reply` tinyint(4) NOT NULL COMMENT '是否可以回复:0-否、1-是',
  `is_url` tinyint(4) NOT NULL COMMENT '是否需要跳转url:0-否、1-是',
  `url` varchar(500) NOT NULL DEFAULT '' COMMENT '跳转url',
  `content` text NOT NULL COMMENT '内容',
  `is_read` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否已读：0-否、1-是',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户消息表';


CREATE TABLE IF NOT EXISTS `tb_hobby` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(255) DEFAULT NULL COMMENT '爱好名称',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='爱好表';

-----------

