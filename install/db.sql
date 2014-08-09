-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2014 at 08:33 AM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pixelimity`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(5) NOT NULL,
  `username` varchar(300) NOT NULL,
  `password` varchar(300) NOT NULL,
  `email` varchar(300) NOT NULL,
  `rp_code` int(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `email`, `rp_code`) VALUES
(1, '{{username}}', '{{password}}', '{{email}}', 0);

--
-- Table structure for table `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `option_key` varchar(300) NOT NULL,
  `value` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`id`, `option_key`, `value`) VALUES
(1, 'site_name', '{{site_name}}'),
(2, 'site_description', '{{site_description}}'),
(3, 'active_theme', 'pixelimity'),
(4, 'admin_portfolio_show', '5'),
(5, 'admin_tags_show', '5'),
(6, 'admin_pages_show', '5'),
(7, 'portfolio_show', '5'),
(8, 'home_image_size', '240,0,auto'),
(9, 'single_image_size', '720,0,auto');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `title` varchar(300) NOT NULL,
  `slug` varchar(300) NOT NULL,
  `link` varchar(300) NOT NULL,
  `description` longtext NOT NULL,
  `seo_keywords` varchar(300) NOT NULL,
  `seo_index` int(1) NOT NULL,
  `seo_follow` int(1) NOT NULL,
  `status` int(1) NOT NULL,
  `publish_date` int(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `portfolio`
--

CREATE TABLE IF NOT EXISTS `portfolio` (
  `id` int(30) NOT NULL,
  `title` varchar(300) NOT NULL,
  `slug` varchar(300) NOT NULL,
  `description` longtext NOT NULL,
  `date` varchar(300) NOT NULL,
  `client` varchar(300) NOT NULL,
  `client_url` varchar(300) NOT NULL,
  `seo_keywords` longtext NOT NULL,
  `seo_follow` int(1) NOT NULL,
  `seo_index` int(1) NOT NULL,
  `status` int(1) NOT NULL,
  `publish_date` int(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_images`
--

CREATE TABLE IF NOT EXISTS `portfolio_images` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `portfolio_id` int(30) NOT NULL,
  `image_name` varchar(300) NOT NULL,
  `image_order` int(30) NOT NULL,
  `as_thumbnail` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=63 ;

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_tags`
--

CREATE TABLE IF NOT EXISTS `portfolio_tags` (
  `id` int(30) NOT NULL,
  `name` varchar(300) NOT NULL,
  `slug` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_tags_rel`
--

CREATE TABLE IF NOT EXISTS `portfolio_tags_rel` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `portfolio_id` int(30) NOT NULL,
  `tag_id` int(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `portfolio_id` (`portfolio_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=246 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
