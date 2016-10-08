/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : act_pdq

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2016-10-08 19:08:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for administrator
-- ----------------------------
DROP TABLE IF EXISTS `administrator`;
CREATE TABLE `administrator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `password` varchar(64) NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `created` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `administrator_username` (`username`),
  KEY `administrator_mobile` (`mobile`),
  KEY `administrator_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of administrator
-- ----------------------------
INSERT INTO `administrator` VALUES ('2', 'baihe', null, '57f85e$476b4b3324395ac09d46da2d20a37c882320b85a', null, null, '1475894966', '1475894966');

-- ----------------------------
-- Table structure for login_attempt
-- ----------------------------
DROP TABLE IF EXISTS `login_attempt`;
CREATE TABLE `login_attempt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `adminId` int(11) DEFAULT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ipAddr` varchar(40) DEFAULT NULL,
  `userAgent` varchar(255) DEFAULT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `login` (`login`),
  KEY `adminId` (`adminId`),
  KEY `created` (`created`),
  KEY `ipAddr` (`ipAddr`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of login_attempt
-- ----------------------------
INSERT INTO `login_attempt` VALUES ('3', '2', 'baihe', '123445', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36', '1475894983');
INSERT INTO `login_attempt` VALUES ('4', '2', 'baihe', '123445', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.116 Safari/537.36', '1475894986');

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(20) NOT NULL,
  `prepay_id` varchar(64) DEFAULT NULL,
  `module` varchar(20) DEFAULT NULL,
  `total_fee` int(11) NOT NULL DEFAULT '0',
  `ispayed` tinyint(1) DEFAULT '0',
  `created` timestamp NULL DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of order
-- ----------------------------

-- ----------------------------
-- Table structure for question
-- ----------------------------
DROP TABLE IF EXISTS `question`;
CREATE TABLE `question` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `reverse` tinyint(4) DEFAULT '0',
  `sort` int(11) DEFAULT '0',
  `module` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of question
-- ----------------------------
INSERT INTO `question` VALUES ('1', '总的来说，我不喜欢让恋人知道自己内心深处的感觉。', '0', '1', '');
INSERT INTO `question` VALUES ('2', '我担心我会被抛弃。', '0', '2', '');
INSERT INTO `question` VALUES ('3', '我觉得跟恋人亲近是一件惬意的事情。', '1', '3', '');
INSERT INTO `question` VALUES ('4', '我很担心我的恋爱关系。', '0', '4', '');
INSERT INTO `question` VALUES ('5', '当恋人开始要跟我亲近时，我发现我自己在退缩。', '0', '5', '');
INSERT INTO `question` VALUES ('6', '我担心恋人不会象我关心他（/她）那样地关心我。', '0', '6', '');
INSERT INTO `question` VALUES ('7', '当恋人希望跟我非常亲近时，我会觉得不自在。', '0', '7', '');
INSERT INTO `question` VALUES ('8', '我有点担心会失去恋人。', '0', '8', '');
INSERT INTO `question` VALUES ('9', '我觉得对恋人开诚布公，不是一件很舒服的事情。', '0', '9', '');
INSERT INTO `question` VALUES ('10', '我常常希望恋人对我的感情和我对恋人的感情一样强烈。', '0', '10', '');
INSERT INTO `question` VALUES ('11', '我想与恋人亲近，但我又总是会退缩不前。', '0', '11', '');
INSERT INTO `question` VALUES ('12', '我常常想与恋人形影不离，但有时这样会把恋人吓跑。', '0', '12', '');
INSERT INTO `question` VALUES ('13', '当恋人跟我过分亲密的时候，我会感到内心紧张。', '0', '13', '');
INSERT INTO `question` VALUES ('14', '我担心一个人独处。', '0', '14', '');
INSERT INTO `question` VALUES ('15', '我愿意把我内心的想法和感觉告诉恋人，我觉得这是一件自在的事情。', '1', '15', '');
INSERT INTO `question` VALUES ('16', '我想跟恋人非常亲密的愿望，有时会把恋人吓跑。', '0', '16', '');
INSERT INTO `question` VALUES ('17', '我试图避免与恋人变得太亲近。', '0', '17', '');
INSERT INTO `question` VALUES ('18', '我需要我的恋人一再地保证他/ 她是爱我的。', '0', '18', '');
INSERT INTO `question` VALUES ('19', '我觉得我比较容易与恋人亲近。', '1', '19', '');
INSERT INTO `question` VALUES ('20', '我觉得自己在要求恋人把更多的感觉，以及对恋爱关系的投入程度表现出来。', '0', '20', '');
INSERT INTO `question` VALUES ('21', '我发现让我依赖恋人，是一件困难的事情。', '0', '21', '');
INSERT INTO `question` VALUES ('22', '我并不是常常担心被恋人抛弃。', '1', '22', '');
INSERT INTO `question` VALUES ('23', '我倾向于不跟恋人过分亲密。', '0', '23', '');
INSERT INTO `question` VALUES ('24', '如果我无法得到恋人的注意和关心，我会心烦意乱或者生气。', '0', '24', '');
INSERT INTO `question` VALUES ('25', '我跟恋人什么事情都讲。', '1', '25', '');
INSERT INTO `question` VALUES ('26', '我发现恋人并不愿意象我所想的那样跟我亲近。', '0', '26', '');
INSERT INTO `question` VALUES ('27', '我经常与恋人讨论我所遇到的问题以及我关心的事情。', '0', '27', '');
INSERT INTO `question` VALUES ('28', '如果我还没有恋人的话，我会感到有点焦虑和不安。', '0', '28', '');
INSERT INTO `question` VALUES ('29', '我觉得依赖恋人是很自在的事情。', '1', '29', '');
INSERT INTO `question` VALUES ('30', '如果恋人不能象我所希望的那样在我身边时，我会感到灰心丧气。', '0', '30', '');
INSERT INTO `question` VALUES ('31', '我并不在意从恋人那里寻找安慰，听取劝告，得到帮助。', '1', '31', '');
INSERT INTO `question` VALUES ('32', '如果在我需要的时候，恋人却不在我身边，我会感到沮丧。', '0', '32', '');
INSERT INTO `question` VALUES ('33', '在需要的时候，我向恋人求助，是很有用的。', '1', '33', '');
INSERT INTO `question` VALUES ('34', '当恋人不赞同我时，我觉得确实是我不好。', '0', '34', '');
INSERT INTO `question` VALUES ('35', '我会在很多事情上向恋人求助，包括寻求安慰和得到承诺。', '1', '35', '');
INSERT INTO `question` VALUES ('36', '当恋人不花时间和我在一起时，我会感到怨恨。', '0', '36', '');

-- ----------------------------
-- Table structure for setting
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `name` varchar(30) NOT NULL,
  `value` text,
  `updated` int(11) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of setting
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openId` varchar(90) NOT NULL DEFAULT '',
  `nickname` varchar(90) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `country` varchar(32) DEFAULT NULL,
  `province` varchar(32) DEFAULT NULL,
  `city` varchar(32) DEFAULT NULL,
  `accessToken` varchar(255) DEFAULT NULL,
  `unionId` varchar(90) DEFAULT NULL,
  `data` text,
  `createTime` int(10) NOT NULL,
  `updateTime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openId` (`openId`),
  KEY `nickname` (`nickname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of users
-- ----------------------------
