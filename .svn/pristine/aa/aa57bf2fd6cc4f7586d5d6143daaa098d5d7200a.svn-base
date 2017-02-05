-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2016-08-04 09:13:36
-- 服务器版本: 5.6.14
-- PHP 版本: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 数据库: `xiaonuo`
--

-- --------------------------------------------------------

--
-- 表的结构 `tb_admin`
--

CREATE TABLE IF NOT EXISTS `tb_admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `login` varchar(255) NOT NULL DEFAULT '' COMMENT '登录ID',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `status` tinyint(1) NOT NULL COMMENT '状态 0 - 禁用 1 - 启用',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='系统管理员表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_application`
--

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

-- --------------------------------------------------------

--
-- 表的结构 `tb_article`
--

CREATE TABLE IF NOT EXISTS `tb_article` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` int(4) NOT NULL DEFAULT '0' COMMENT '类型',
  `title` varchar(255) DEFAULT NULL COMMENT '班级名称',
  `link` varchar(5000) NOT NULL COMMENT '链接',
  `body` text COMMENT '内容',
  `start` datetime NOT NULL COMMENT '开始时间',
  `end` datetime NOT NULL COMMENT '结束时间',
  `status` tinyint(4) NOT NULL COMMENT '状态：0 - 禁用 1 - 有效',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_article_auth`
--

CREATE TABLE IF NOT EXISTS `tb_article_auth` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `article_id` int(10) NOT NULL COMMENT '文章ID',
  `type` int(4) NOT NULL COMMENT '可见类型',
  `value` int(10) NOT NULL COMMENT '可见类型值',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章可见权限表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_bindparent_apply`
--

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
  KEY `phone` (`phone`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='家长绑定申请表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_captcha`
--

CREATE TABLE IF NOT EXISTS `tb_captcha` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(255) NOT NULL COMMENT '用户手机号码',
  `captcha` varchar(255) NOT NULL COMMENT '验证码',
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='手机验证码表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_class`
--

CREATE TABLE IF NOT EXISTS `tb_class` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `school_id` int(10) NOT NULL COMMENT '学校ID',
  `title` varchar(255) DEFAULT NULL COMMENT '班级名称',
  `start` date NOT NULL COMMENT '第一次入学时间，用于计算年级',
  `status` tinyint(4) NOT NULL COMMENT '状态：0 - 禁用 1 - 有效',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='班级表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_detection_claim`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='检测数据认领表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_device`
--

CREATE TABLE IF NOT EXISTS `tb_device` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `school_id` int(10) NOT NULL COMMENT '学校ID',
  `no` varchar(255) DEFAULT NULL COMMENT '编号',
  `status` tinyint(4) NOT NULL COMMENT '状态：0 - 禁用 1 - 有效',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`),
  UNIQUE KEY `no` (`no`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='设备表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_dream`
--

CREATE TABLE IF NOT EXISTS `tb_dream` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) DEFAULT NULL COMMENT '角色名称',
  `img_url` varchar(500) DEFAULT NULL COMMENT '角色图片',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='学生梦想表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_file`
--

CREATE TABLE IF NOT EXISTS `tb_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名称',
  `file_path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件存放路径',
  `file_mime` varchar(255) NOT NULL COMMENT '文件 mime，如：image/jpeg、text/csv',
  `file_size` mediumint(10) NOT NULL COMMENT '文件大小（字节数）',
  `usage_id` mediumint(10) NOT NULL COMMENT '关联ID',
  `usage_type` int(4) NOT NULL COMMENT '关联类型： 101 - 识别图片 102 - 检测头像',
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文件表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_help`
--

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

-- --------------------------------------------------------

--
-- 表的结构 `tb_message`
--

CREATE TABLE IF NOT EXISTS `tb_message` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `parent_id` int(10) NOT NULL COMMENT '家长ID',
  `student_id` int(10) NOT NULL COMMENT '学生ID',
  `type` int(4) DEFAULT NULL COMMENT '消息类型 1 - 文字 2 - 语音',
  `content` varchar(1000) NOT NULL COMMENT '消息内容',
  `data` text NOT NULL COMMENT '序列化其他数据',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='消息表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_parent`
--

CREATE TABLE IF NOT EXISTS `tb_parent` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `weichat_id` int(10) NOT NULL DEFAULT '0' COMMENT '微信用户ID',
  `name` varchar(255) DEFAULT NULL COMMENT '姓名',
  `phone` varchar(20) NOT NULL COMMENT '手机号码',
  `status` tinyint(4) NOT NULL COMMENT '状态：0 - 禁用 1 - 有效',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`),
  KEY `phone` (`phone`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='家长表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_relation`
--

CREATE TABLE IF NOT EXISTS `tb_relation` (
  `id` int(10) NOT NULL COMMENT 'ID',
  `title` varchar(10) NOT NULL COMMENT '称谓',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='关系表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_report_month`
--

CREATE TABLE IF NOT EXISTS `tb_report_month` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `student_id` int(10) NOT NULL COMMENT '学生ID',
  `start` datetime NOT NULL COMMENT '月开始时间',
  `end` datetime NOT NULL COMMENT '月结束时间',
  `reported` datetime NOT NULL COMMENT '报告显示时间',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='学生检测月报表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_report_week`
--

CREATE TABLE IF NOT EXISTS `tb_report_week` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `student_id` int(10) NOT NULL COMMENT '学生ID',
  `start` datetime NOT NULL COMMENT '周开始时间',
  `end` datetime NOT NULL COMMENT '周结束时间',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='学生检测周报表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_school`
--

CREATE TABLE IF NOT EXISTS `tb_school` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(255) DEFAULT NULL COMMENT '学习名称',
  `address` varchar(255) DEFAULT NULL COMMENT '签名内容',
  `phone` varchar(255) DEFAULT NULL COMMENT '联系电话',
  `key` varchar(255) DEFAULT NULL COMMENT '正在使用的KEY',
  `key_new` varchar(255) DEFAULT NULL COMMENT '新生成的KEY',
  `status` tinyint(4) NOT NULL COMMENT '状态：0 - 禁用 1 - 有效',
  `key_new_time` datetime DEFAULT NULL COMMENT '新KEY值生成的时间',
  `key_active_time` datetime DEFAULT NULL COMMENT 'KEY值生效时间',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='学校表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_school_admin`
--

CREATE TABLE IF NOT EXISTS `tb_school_admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `school_id` int(10) NOT NULL COMMENT '学校ID',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `login` varchar(255) NOT NULL DEFAULT '' COMMENT '登录ID',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `status` tinyint(1) NOT NULL COMMENT '状态 0 - 禁用 1 - 启用',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='学校管理员表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_school_parent`
--

CREATE TABLE IF NOT EXISTS `tb_school_parent` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `school_id` int(10) NOT NULL COMMENT '学校ID',
  `name` varchar(255) DEFAULT NULL COMMENT '姓名',
  `phone` varchar(20) NOT NULL COMMENT '手机号码',
  `type` int(4) NOT NULL COMMENT '账号类型 1 - 主账号（具体家庭成员管理权限）',
  `status` tinyint(4) NOT NULL COMMENT '状态：0 - 禁用 1 - 有效',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`),
  KEY `phone` (`phone`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='学校家长表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_sign_type`
--

CREATE TABLE IF NOT EXISTS `tb_sign_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `school_id` int(11) NOT NULL COMMENT '学校ID',
  `title` varchar(255) NOT NULL COMMENT '类型名称',
  `in_time` time NOT NULL COMMENT '签到时间',
  `out_time` time NOT NULL COMMENT '签退时间',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='签到类型表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_sms`
--

CREATE TABLE IF NOT EXISTS `tb_sms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(20) NOT NULL COMMENT '手机号码',
  `message` mediumtext NOT NULL,
  `status` smallint(6) NOT NULL COMMENT '发送状态 1 - 未发送 2 - 发送中 3 - 发送成功 4 - 发送失败',
  `result` smallint(6) NOT NULL COMMENT '发送状态码',
  `data` mediumtext NOT NULL COMMENT '序列化发送结果数组',
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='短信记录表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_staff`
--

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

-- --------------------------------------------------------

--
-- 表的结构 `tb_staff_class`
--

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

-- --------------------------------------------------------

--
-- 表的结构 `tb_staff_signdate`
--

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
  KEY `staff_date` (`staff_id`,`sign_timestamp`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='职工签到状态表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_staff_signrecord`
--

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
  KEY `staff_date` (`staff_id`,`sign_timestamp`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='职工签到记录表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_student`
--

CREATE TABLE IF NOT EXISTS `tb_student` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `school_id` int(10) NOT NULL COMMENT '学校ID',
  `class_id` int(10) NOT NULL COMMENT '班级ID',
  `name` varchar(255) DEFAULT NULL COMMENT '姓名',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '小名',
  `hobby` varchar(255) NOT NULL DEFAULT '' COMMENT '爱好',
  `dream_id` int(11) NOT NULL DEFAULT '0' COMMENT '梦想ID',
  `gender` tinyint(4) NOT NULL COMMENT '性别 1 - 男 2 - 女',
  `birthday` date NOT NULL COMMENT '生日',
  `status` tinyint(4) NOT NULL COMMENT '状态：0 - 禁用 1 - 有效',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`),
  KEY `name_birthday` (`name`,`birthday`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='学生表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_student_away_record`
--

CREATE TABLE IF NOT EXISTS `tb_student_away_record` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `student_id` int(10) NOT NULL COMMENT '学生ID',
  `parent_id` int(10) NOT NULL COMMENT '家长ID',
  `img` varchar(500) NOT NULL COMMENT '接小孩图片',
  `sub_img` varchar(500) NOT NULL DEFAULT '' COMMENT '辅助照片',
  `created` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`,`created`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='接学生记录流水';

-- --------------------------------------------------------

--
-- 表的结构 `tb_student_detection`
--

CREATE TABLE IF NOT EXISTS `tb_student_detection` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `student_id` int(10) NOT NULL COMMENT '学生ID',
  `height` int(4) NOT NULL COMMENT '身高，厘米',
  `weight` int(4) NOT NULL COMMENT '体重，克',
  `temperature` float(3,1) NOT NULL COMMENT '体温，度',
  `env_temperature` float(3,1) DEFAULT NULL COMMENT '环境温度',
  `raw_temperature` float(3,1) DEFAULT NULL COMMENT '原始温度',
  `temperature_threshold` float(3,1) DEFAULT NULL COMMENT '体温阈值',
  `first` tinyint(4) NOT NULL COMMENT '当天第一条 0 - 否 1 - 是',
  `lastest` tinyint(4) NOT NULL COMMENT '当天最后一条 0 - 否 1 - 是',
  `terminal_img_id` varchar(255) NOT NULL DEFAULT '' COMMENT '终端上传的img_id',
  `file_img_id` int(11) NOT NULL DEFAULT '0' COMMENT '保存的缩略图文件id',
  `org_img_url` varchar(500) NOT NULL DEFAULT '' COMMENT '原始图片url',
  `weichat_num` int(11) NOT NULL DEFAULT '0' COMMENT '主动推送次数',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态：1-正常、0-退回',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='学生检测数据表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_student_parent`
--

CREATE TABLE IF NOT EXISTS `tb_student_parent` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `school_id` int(10) NOT NULL COMMENT '学校ID',
  `class_id` int(10) NOT NULL COMMENT '班级ID',
  `student_id` int(10) NOT NULL COMMENT '学生ID',
  `parent_id` int(10) NOT NULL COMMENT '家长ID',
  `rfid` varchar(255) DEFAULT NULL COMMENT 'RFID',
  `relation_id` tinyint(4) NOT NULL COMMENT '关系ID 1 - 父亲 2 - 母亲 3 - 爷爷 4 - 奶奶 5 - 外公 6 - 外婆',
  `relation_title` varchar(255) DEFAULT NULL COMMENT '关系名称',
  `qrcode_url` varchar(500) NOT NULL DEFAULT '' COMMENT '接小孩二维码',
  `qrcode_school_key` varchar(255) NOT NULL DEFAULT '' COMMENT '二维码中所使用的学校KEY',
  `created` datetime NOT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='学生家长表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_system`
--

CREATE TABLE IF NOT EXISTS `tb_system` (
  `name` varchar(255) DEFAULT NULL COMMENT '配置名称',
  `description` text COMMENT '描述',
  `k` varchar(255) NOT NULL COMMENT '键',
  `v` text NOT NULL COMMENT '值',
  `type` int(4) DEFAULT NULL COMMENT '类型 0 - textarea 1 - full html',
  `created` datetime DEFAULT NULL COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统配置表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_user_message`
--

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

-- --------------------------------------------------------

--
-- 表的结构 `tb_weichat`
--

CREATE TABLE IF NOT EXISTS `tb_weichat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '微信用户ID',
  `openid` varchar(255) NOT NULL DEFAULT '' COMMENT '用户OpenID',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0-未知，1-男，2-女',
  `province` varchar(255) NOT NULL DEFAULT '' COMMENT '省',
  `city` varchar(255) NOT NULL DEFAULT '' COMMENT '市',
  `country` varchar(255) NOT NULL DEFAULT '' COMMENT '国家',
  `headimgurl` varchar(500) NOT NULL DEFAULT '' COMMENT '用户头像URL',
  `unionid` varchar(255) DEFAULT '' COMMENT '用户UnionID',
  `followed` tinyint(4) NOT NULL DEFAULT '0' COMMENT '关注状态：0-未关注，1-已关注',
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '删除状态：0-未删除，1-删除',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='微信用户表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_weichat_bind`
--

CREATE TABLE IF NOT EXISTS `tb_weichat_bind` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weichat_id` int(11) NOT NULL COMMENT '微信ID',
  `phone` varchar(20) NOT NULL COMMENT '手机号',
  `usage_type` tinyint(4) NOT NULL COMMENT '关联用户类型：21-家长、31-园长、32-老师、33-保健医生、34-后勤人员',
  `deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '删除状态：0-未删除，1-删除',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `weichat_id` (`weichat_id`),
  KEY `phone` (`phone`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='微信用户绑定表';

-- --------------------------------------------------------

--
-- 表的结构 `tb_weichat_push`
--

CREATE TABLE IF NOT EXISTS `tb_weichat_push` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `open_id` varchar(100) DEFAULT NULL COMMENT '微信Open ID',
  `message` mediumtext NOT NULL,
  `status` smallint(6) NOT NULL COMMENT '发送状态 1 - 未发送 2 - 发送中 3 - 发送成功 4 - 发送失败',
  `result` varchar(10) NOT NULL COMMENT '序列化发送结果数组',
  `data` mediumtext NOT NULL COMMENT '序列化发送结果数组',
  `send_time` datetime NOT NULL COMMENT '发送时间',
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='微信推送信息表';
