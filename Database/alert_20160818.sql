-- 20160818未更新（20160819已更新正式）

ALTER TABLE `tb_help` DROP `body`;

ALTER TABLE  `tb_help` ADD  `icon_img` VARCHAR( 255 ) NOT NULL DEFAULT  '' COMMENT  'icon图标' AFTER  `title` ;

ALTER TABLE  `tb_help` ADD  `link` VARCHAR( 500 ) NOT NULL DEFAULT  '' COMMENT  '链接地址' AFTER  `icon_img` ;

ALTER TABLE  `tb_help` ADD  `weight` INT NOT NULL DEFAULT  '0' COMMENT  '排序值，值小在前' AFTER  `link` ;


ALTER TABLE  `tb_detection_claim` ADD  `school_id` INT NULL COMMENT  '学校ID' AFTER  `id` ;

-----------

