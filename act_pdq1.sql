/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50641
Source Host           : localhost:3306
Source Database       : act_pdq

Target Server Type    : MYSQL
Target Server Version : 50641
File Encoding         : 65001

Date: 2018-12-11 14:38:57
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
-- Table structure for code
-- ----------------------------
DROP TABLE IF EXISTS `code`;
CREATE TABLE `code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  `given` tinyint(1) DEFAULT '0' COMMENT '是否给出',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未使用1已使用',
  `user_id` int(11) DEFAULT NULL COMMENT '使用用户ID',
  `order_id` varchar(50) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`code`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of code
-- ----------------------------
INSERT INTO `code` VALUES ('1', 'c51874398c36424ced53560cfe921935', 'pdq', '1', '1', '1', null, '1476354036', '1476413461');
INSERT INTO `code` VALUES ('2', 'a998bc2b18910c8cbc1932caf6d908e8', 'pdq', '0', '1', '1', '2016102615271208824', '1476354036', '1477471195');
INSERT INTO `code` VALUES ('3', 'c0cc3efa891e83982b99e7a6abc5aa4c', 'pdq', '0', '1', '1', '2016110418125505713', '1476354036', '1478254653');
INSERT INTO `code` VALUES ('4', '3587f35f0e0a0bae90ef2ab896a8430c', 'pdq', '1', '0', null, null, '1476354036', '1476354036');
INSERT INTO `code` VALUES ('5', '023cfa737e00d46d2f33a4344dabfc2f', 'pdq', '0', '0', null, null, '1476354036', '1476354036');
INSERT INTO `code` VALUES ('6', '3115b0500f5b82e3fb45ac7211ec678d', 'pdq', '1', '1', '1', '2016101517452502817', '1476354036', '1476526594');
INSERT INTO `code` VALUES ('7', '0affcb319d93209ba30c48ffbbf11aae', 'pdq', '0', '1', '1', null, '1476354036', '1476501984');
INSERT INTO `code` VALUES ('8', '9e69975634c2c354130fb42c35d3959c', 'pdq', '1', '1', '1', null, '1476354036', '1476441345');
INSERT INTO `code` VALUES ('9', '4f7ecdc15acdfe303019aee52169ce7d', 'pdq', '0', '1', '1', null, '1476354036', '1476429553');
INSERT INTO `code` VALUES ('10', 'bd48127721ae3255b70555eb0951a669', 'pdq', '0', '1', '1', '2016102019203903779', '1476354036', '1476962548');

-- ----------------------------
-- Table structure for jsapi_ticket
-- ----------------------------
DROP TABLE IF EXISTS `jsapi_ticket`;
CREATE TABLE `jsapi_ticket` (
  `module` varchar(50) NOT NULL,
  `value` text,
  `expire_at` int(11) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jsapi_ticket
-- ----------------------------
INSERT INTO `jsapi_ticket` VALUES ('pdq', null, null, null, null);

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
  `user_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `order_id` varchar(50) CHARACTER SET utf8 NOT NULL,
  `prepay_id` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `transaction_id` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '微信支付订单号',
  `total_fee` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '0' COMMENT '0待支付1已支付2已失效3出错订单',
  `type` varchar(20) CHARACTER SET utf8 DEFAULT NULL COMMENT '支付或兑换码 wxpay or code',
  `data` text CHARACTER SET utf8 COMMENT '测试结果',
  `sex` int(2) DEFAULT '0' COMMENT '1男0女',
  `avoid` float(11,2) DEFAULT NULL,
  `anxious` float(11,2) DEFAULT NULL,
  `response` text COMMENT 'wx返回结果',
  `expire_at` int(11) DEFAULT '0',
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of order
-- ----------------------------
INSERT INTO `order` VALUES ('3', '1', '1', '2016110418125505713', '', '', '1', '1', 'code', '害怕型(重度)', '1', '1.46', '2.86', '', '0', '1478254653', '1478254653');
INSERT INTO `order` VALUES ('4', '1', '1', '2016110418180709964', '', '', '1', '0', '', '害怕型(典型)', '0', '1.00', '0.38', '', '0', '1478254687', '1478254687');
INSERT INTO `order` VALUES ('5', '1', '1', '2016110716085600495', '', '', '1', '0', '', '轻视型(轻度)', '1', '0.26', '-1.64', '', '0', '1478506136', '1478506136');
INSERT INTO `order` VALUES ('6', '1', '1', '2018070316043307537', 'wx031604401151913ac47040fd0901509249', '', '1', '0', '', '轻视型(典型)', '1', '0.81', '-0.22', '{\"errcode\":0,\"errmsg\":\"\",\"prepay_id\":\"wx031604401151913ac47040fd0901509249\",\"res\":{\"xml\":\"<xml><return_code><![CDATA[SUCCESS]]><\\/return_code>\\n<return_msg><![CDATA[OK]]><\\/return_msg>\\n<appid><![CDATA[wxcdde906c2ef572c5]]><\\/appid>\\n<mch_id><![CDATA[1396588502]]><\\/mch_id>\\n<nonce_str><![CDATA[RJUBzUy7HGQLbhmb]]><\\/nonce_str>\\n<sign><![CDATA[D5BB7C67BF13FF74F26B76AA5AD84EF4]]><\\/sign>\\n<result_code><![CDATA[SUCCESS]]><\\/result_code>\\n<prepay_id><![CDATA[wx031604401151913ac47040fd0901509249]]><\\/prepay_id>\\n<trade_type><![CDATA[JSAPI]]><\\/trade_type>\\n<\\/xml>\",\"data\":{\"return_code\":\"SUCCESS\",\"return_msg\":\"OK\",\"appid\":\"wxcdde906c2ef572c5\",\"mch_id\":\"1396588502\",\"nonce_str\":\"RJUBzUy7HGQLbhmb\",\"sign\":\"D5BB7C67BF13FF74F26B76AA5AD84EF4\",\"result_code\":\"SUCCESS\",\"prepay_id\":\"wx031604401151913ac47040fd0901509249\",\"trade_type\":\"JSAPI\"}}}', '1530605659', '1530605079', '1530605079');

-- ----------------------------
-- Table structure for product
-- ----------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `detail` text,
  `total_fee` int(11) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of product
-- ----------------------------
INSERT INTO `product` VALUES ('1', '爱情实验室-查看结果', '百合网·爱情实验室', '1', 'pdq');

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
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openId` varchar(90) NOT NULL DEFAULT '',
  `nickname` varchar(90) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `sex` tinyint(1) DEFAULT NULL,
  `country` varchar(32) DEFAULT NULL,
  `province` varchar(32) DEFAULT NULL,
  `city` varchar(32) DEFAULT NULL,
  `isPayed` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否付款用户',
  `accessToken` varchar(255) DEFAULT NULL,
  `tokenExpires` int(11) DEFAULT NULL,
  `refreshToken` varchar(255) DEFAULT NULL,
  `unionId` varchar(90) DEFAULT NULL,
  `result` text COMMENT '最新测试结果',
  `data` text,
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openId` (`openId`) USING BTREE,
  KEY `nickname` (`nickname`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'oxdAfs-xVXu3JHJS72AycL3UkEPY', 'Dantis', 'http://wx.qlogo.cn/mmopen/XnhvPOTqibkB957my628r5wb2wHlhddyTqsIbAEn1KUUSqogu4WWDF6RsdVHGTqt3IJWewcc1wTqvR7GHEDl9HhrmqWFRwVNu/0', '1', '中国', '广东', '深圳', '0', 'hZCUS-y6EgCoV9odQlBO62TpTdFur_Cz9FMunigaJDi2yIT-4LLXvMXv_ZYtxM536va4dLfP9lcH5fmmdcX08pgMoKMsHq78xvw3tUmjMgk', '1476249020', '013oyJxp47wFTU6k2KWcAl6iDFjXX1xmhGRRXbcExPN3T2XyKA2snaoEJAIcdFkRx1_7AalRknvbRtcBL1wk0CtGRngMfv6K0aGJBxESfXs', '', '轻视型(典型)', '{\"openid\":\"oxdAfs-xVXu3JHJS72AycL3UkEPY\",\"nickname\":\"Dantis\",\"sex\":1,\"language\":\"zh_CN\",\"city\":\"\\u6df1\\u5733\",\"province\":\"\\u5e7f\\u4e1c\",\"country\":\"\\u4e2d\\u56fd\",\"headimgurl\":\"http:\\/\\/wx.qlogo.cn\\/mmopen\\/XnhvPOTqibkB957my628r5wb2wHlhddyTqsIbAEn1KUUSqogu4WWDF6RsdVHGTqt3IJWewcc1wTqvR7GHEDl9HhrmqWFRwVNu\\/0\",\"privilege\":[]}', '1476241262', '1530605072');
