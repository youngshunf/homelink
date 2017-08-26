/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50505
 Source Host           : localhost
 Source Database       : homelink

 Target Server Type    : MySQL
 Target Server Version : 50505
 File Encoding         : utf-8

 Date: 01/23/2017 20:37:24 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `interview_address`
-- ----------------------------
DROP TABLE IF EXISTS `interview_address`;
CREATE TABLE `interview_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `district_code` varchar(255) DEFAULT NULL COMMENT '大区编号',
  `district_name` varchar(255) DEFAULT NULL COMMENT '大区名',
  `year_month` varchar(255) DEFAULT NULL COMMENT '月份',
  `time` bigint(20) DEFAULT NULL COMMENT '时间',
  `address` varchar(255) DEFAULT NULL COMMENT '地点',
  `created_at` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `interview_data`
-- ----------------------------
DROP TABLE IF EXISTS `interview_data`;
CREATE TABLE `interview_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `work_number` varchar(255) DEFAULT NULL COMMENT '工号',
  `name` varchar(255) DEFAULT NULL COMMENT '姓名',
  `level` varchar(20) DEFAULT NULL COMMENT '级别',
  `mobile` varchar(20) DEFAULT NULL COMMENT '联系方式',
  `sale_district` varchar(255) DEFAULT NULL COMMENT '营销区域',
  `business_district` varchar(255) DEFAULT NULL COMMENT '业务区域',
  `shop` varchar(255) DEFAULT NULL COMMENT '门店',
  `age` varchar(255) DEFAULT NULL COMMENT '年龄',
  `marriage` varchar(255) DEFAULT NULL COMMENT '婚姻状况',
  `join_date` varchar(255) DEFAULT NULL COMMENT '入职日期',
  `top_edu` varchar(255) DEFAULT NULL COMMENT '最高教育程度',
  `teacher` varchar(255) DEFAULT NULL COMMENT '认证讲师',
  `score` varchar(255) DEFAULT NULL COMMENT '博学成绩',
  `qual` varchar(255) DEFAULT NULL COMMENT '精英社资格',
  `year_yellow` varchar(255) DEFAULT NULL COMMENT '一年内黄线',
  `year_sue` varchar(255) DEFAULT NULL COMMENT '一年内投诉',
  `half_score` varchar(255) DEFAULT NULL COMMENT '半年业绩',
  `half_range` varchar(255) DEFAULT NULL COMMENT '半年业绩大区排名',
  `co_single` varchar(255) DEFAULT NULL COMMENT '合作单边比',
  `co_single_range` varchar(255) DEFAULT NULL COMMENT '合作单边比大区排名',
  `half_qual` varchar(255) DEFAULT NULL COMMENT '半年带看质量',
  `half_record` varchar(255) DEFAULT NULL COMMENT '半年录入客户量',
  `year_month` varchar(255) DEFAULT NULL,
  `created_at` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
