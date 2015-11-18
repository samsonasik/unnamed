-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Време на генериране: 
-- Версия на сървъра: 5.5.32-log
-- Версия на PHP: 5.5.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- БД: `xj`
--
CREATE DATABASE IF NOT EXISTS `xj` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `xj`;

-- --------------------------------------------------------

--
-- Структура на таблица `administrator`
--

CREATE TABLE IF NOT EXISTS `administrator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Схема на данните от таблица `administrator`
--

INSERT INTO `administrator` (`id`, `user`) VALUES
(3, 4),
(4, 19);

-- --------------------------------------------------------

--
-- Структура на таблица `adminmenu`
--

CREATE TABLE IF NOT EXISTS `adminmenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(150) DEFAULT NULL,
  `menuOrder` int(11) NOT NULL,
  `description` varchar(150) DEFAULT NULL,
  `parent` int(11) NOT NULL,
  `controller` varchar(50) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Схема на данните от таблица `adminmenu`
--

INSERT INTO `adminmenu` (`id`, `caption`, `menuOrder`, `description`, `parent`, `controller`, `action`, `class`) VALUES
(1, 'Admin menu', 1, 'this is a description2', 0, 'admin-menu', 'index', 'fa-list-ol'),
(2, 'Add admin menu', 1, '', 1, 'admin-menu', 'add', 'fa-plus'),
(3, 'Menu', 0, '', 0, 'menu', 'index', 'fa-list'),
(4, 'Add new menu', 0, '', 3, 'menu', 'add', 'fa-plus'),
(5, 'Content', 0, '', 0, 'content', 'index', 'fa-newspaper-o'),
(6, 'Administrator', 0, '', 0, 'administrator', 'index', 'fa-user'),
(7, 'Add administrator', 0, '', 6, 'administrator', 'add', ''),
(8, 'Add content', 0, '', 5, 'content', 'add', 'fa-plus'),
(9, 'Language', 0, '', 0, 'language', 'index', 'fa-language'),
(11, 'User', 1, '', 0, 'user', 'index', 'fa-group'),
(12, 'Add language', 0, '', 9, 'language', 'add', 'fa-plus'),
(13, 'Settings', 0, '', 0, 'settings', 'general', 'fa-spin fa-cogs'),
(14, 'Registration', 0, '', 13, 'settings', 'registration', 'fa-sign-in'),
(15, 'Mail', 0, '', 13, 'settings', 'mail', 'fa-envelope'),
(16, 'Posts per page', 0, '', 13, 'settings', 'posts', 'fa-caret-left'),
(17, 'Discussion', 0, '', 13, 'settings', 'discussion', 'fa-commenting'),
(19, 'Translations', 1, 'Language specific translations', 9, 'language', 'translations', 'fa-globe');

-- --------------------------------------------------------

--
-- Структура на таблица `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `preview` varchar(100) DEFAULT NULL,
  `text` text,
  `menuOrder` int(11) NOT NULL,
  `type` smallint(6) NOT NULL,
  `date` varchar(255) NOT NULL,
  `menu` int(11) NOT NULL,
  `language` int(11) NOT NULL,
  `titleLink` varchar(255) DEFAULT NULL,
  `active` smallint(6) NOT NULL,
  `author` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Схема на данните от таблица `content`
--

INSERT INTO `content` (`id`, `title`, `preview`, `text`, `menuOrder`, `type`, `date`, `menu`, `language`, `titleLink`, `active`, `author`) VALUES
(1, 'Yahoo &amp; Bing', '', 'text1234321', 1, 0, '2014-10-23 10:33:39', 20, 1, 'yahoo-amp-bing', 1, 0),
(2, 'Google', '', 'test4e za google', 2, 0, '2014-10-23 10:43:36', 0, 1, NULL, 1, 0),
(5, 'Best local position', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 1, 0, '2014-10-23 17:12:46', 9, 1, 'best-local-position', 1, 0),
(6, 'Create impressive reports', '', '', 1, 0, '2014-10-24 09:30:35', 7, 1, NULL, 1, 0),
(7, 'Everything you need', '', '', 1, 0, '2014-10-24 09:37:12', 4, 1, NULL, 1, 0),
(8, 'Get high search ranking', '', '', 1, 0, '2014-10-24 09:49:03', 5, 1, NULL, 1, 0),
(9, 'Innovative technology', '', 'sfgdfhdfhddghdghdhdgh', 1, 0, '2014-10-24 09:49:57', 8, 1, 'innovative-technology', 1, 0),
(11, 'about', '', 'pak test4e doctrine', 1, 0, '2014-10-24 09:51:50', 3, 1, 'track-of-your-positions', 1, 0),
(13, 'Why use test', '', 'test4e2333333333333333333333333', 16, 0, '0000-00-00 00:00:00', 3, 1, 'why-use-test', 1, 0),
(16, 'lorem news', '', '', 1, 1, '2014-10-24 10:45:27', 0, 1, 'lorem-ipsum', 1, 0),
(17, 'trerwt', '', '', 1, 1, '2014-10-24 11:13:41', 0, 1, 'trerwt', 1, 0),
(18, 'sfgsg', '', '', 1, 1, '2014-10-24 11:53:43', 0, 1, 'sfgsg', 1, 0),
(19, 'wregdth', '', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 1, 1, '2014-10-24 12:50:10', 0, 1, 'wregdth', 1, 0);

-- --------------------------------------------------------

--
-- Структура на таблица `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(2) NOT NULL DEFAULT '',
  `country_name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=243 ;

--
-- Схема на данните от таблица `countries`
--

INSERT INTO `countries` (`id`, `country_code`, `country_name`) VALUES
(1, 'US', 'United States'),
(2, 'CA', 'Canada'),
(3, 'AF', 'Afghanistan'),
(4, 'AL', 'Albania'),
(5, 'DZ', 'Algeria'),
(6, 'DS', 'American Samoa'),
(7, 'AD', 'Andorra'),
(8, 'AO', 'Angola'),
(9, 'AI', 'Anguilla'),
(10, 'AQ', 'Antarctica'),
(11, 'AG', 'Antigua and/or Barbuda'),
(12, 'AR', 'Argentina'),
(13, 'AM', 'Armenia'),
(14, 'AW', 'Aruba'),
(15, 'AU', 'Australia'),
(16, 'AT', 'Austria'),
(17, 'AZ', 'Azerbaijan'),
(18, 'BS', 'Bahamas'),
(19, 'BH', 'Bahrain'),
(20, 'BD', 'Bangladesh'),
(21, 'BB', 'Barbados'),
(22, 'BY', 'Belarus'),
(23, 'BE', 'Belgium'),
(24, 'BZ', 'Belize'),
(25, 'BJ', 'Benin'),
(26, 'BM', 'Bermuda'),
(27, 'BT', 'Bhutan'),
(28, 'BO', 'Bolivia'),
(29, 'BA', 'Bosnia and Herzegovina'),
(30, 'BW', 'Botswana'),
(31, 'BV', 'Bouvet Island'),
(32, 'BR', 'Brazil'),
(33, 'IO', 'British lndian Ocean Territory'),
(34, 'BN', 'Brunei Darussalam'),
(35, 'BG', 'Bulgaria'),
(36, 'BF', 'Burkina Faso'),
(37, 'BI', 'Burundi'),
(38, 'KH', 'Cambodia'),
(39, 'CM', 'Cameroon'),
(40, 'CV', 'Cape Verde'),
(41, 'KY', 'Cayman Islands'),
(42, 'CF', 'Central African Republic'),
(43, 'TD', 'Chad'),
(44, 'CL', 'Chile'),
(45, 'CN', 'China'),
(46, 'CX', 'Christmas Island'),
(47, 'CC', 'Cocos (Keeling) Islands'),
(48, 'CO', 'Colombia'),
(49, 'KM', 'Comoros'),
(50, 'CG', 'Congo'),
(51, 'CK', 'Cook Islands'),
(52, 'CR', 'Costa Rica'),
(53, 'HR', 'Croatia (Hrvatska)'),
(54, 'CU', 'Cuba'),
(55, 'CY', 'Cyprus'),
(56, 'CZ', 'Czech Republic'),
(57, 'DK', 'Denmark'),
(58, 'DJ', 'Djibouti'),
(59, 'DM', 'Dominica'),
(60, 'DO', 'Dominican Republic'),
(61, 'TP', 'East Timor'),
(62, 'EC', 'Ecuador'),
(63, 'EG', 'Egypt'),
(64, 'SV', 'El Salvador'),
(65, 'GQ', 'Equatorial Guinea'),
(66, 'ER', 'Eritrea'),
(67, 'EE', 'Estonia'),
(68, 'ET', 'Ethiopia'),
(69, 'FK', 'Falkland Islands (Malvinas)'),
(70, 'FO', 'Faroe Islands'),
(71, 'FJ', 'Fiji'),
(72, 'FI', 'Finland'),
(73, 'FR', 'France'),
(74, 'FX', 'France, Metropolitan'),
(75, 'GF', 'French Guiana'),
(76, 'PF', 'French Polynesia'),
(77, 'TF', 'French Southern Territories'),
(78, 'GA', 'Gabon'),
(79, 'GM', 'Gambia'),
(80, 'GE', 'Georgia'),
(81, 'DE', 'Germany'),
(82, 'GH', 'Ghana'),
(83, 'GI', 'Gibraltar'),
(84, 'GR', 'Greece'),
(85, 'GL', 'Greenland'),
(86, 'GD', 'Grenada'),
(87, 'GP', 'Guadeloupe'),
(88, 'GU', 'Guam'),
(89, 'GT', 'Guatemala'),
(90, 'GN', 'Guinea'),
(91, 'GW', 'Guinea-Bissau'),
(92, 'GY', 'Guyana'),
(93, 'HT', 'Haiti'),
(94, 'HM', 'Heard and Mc Donald Islands'),
(95, 'HN', 'Honduras'),
(96, 'HK', 'Hong Kong'),
(97, 'HU', 'Hungary'),
(98, 'IS', 'Iceland'),
(99, 'IN', 'India'),
(100, 'ID', 'Indonesia'),
(101, 'IR', 'Iran (Islamic Republic of)'),
(102, 'IQ', 'Iraq'),
(103, 'IE', 'Ireland'),
(104, 'IL', 'Israel'),
(105, 'IT', 'Italy'),
(106, 'CI', 'Ivory Coast'),
(107, 'JM', 'Jamaica'),
(108, 'JP', 'Japan'),
(109, 'JO', 'Jordan'),
(110, 'KZ', 'Kazakhstan'),
(111, 'KE', 'Kenya'),
(112, 'KI', 'Kiribati'),
(113, 'KP', 'Korea, Democratic People''s Republic of'),
(114, 'KR', 'Korea, Republic of'),
(115, 'XK', 'Kosovo'),
(116, 'KW', 'Kuwait'),
(117, 'KG', 'Kyrgyzstan'),
(118, 'LA', 'Lao People''s Democratic Republic'),
(119, 'LV', 'Latvia'),
(120, 'LB', 'Lebanon'),
(121, 'LS', 'Lesotho'),
(122, 'LR', 'Liberia'),
(123, 'LY', 'Libyan Arab Jamahiriya'),
(124, 'LI', 'Liechtenstein'),
(125, 'LT', 'Lithuania'),
(126, 'LU', 'Luxembourg'),
(127, 'MO', 'Macau'),
(128, 'MK', 'Macedonia'),
(129, 'MG', 'Madagascar'),
(130, 'MW', 'Malawi'),
(131, 'MY', 'Malaysia'),
(132, 'MV', 'Maldives'),
(133, 'ML', 'Mali'),
(134, 'MT', 'Malta'),
(135, 'MH', 'Marshall Islands'),
(136, 'MQ', 'Martinique'),
(137, 'MR', 'Mauritania'),
(138, 'MU', 'Mauritius'),
(139, 'TY', 'Mayotte'),
(140, 'MX', 'Mexico'),
(141, 'FM', 'Micronesia, Federated States of'),
(142, 'MD', 'Moldova, Republic of'),
(143, 'MC', 'Monaco'),
(144, 'MN', 'Mongolia'),
(145, 'ME', 'Montenegro'),
(146, 'MS', 'Montserrat'),
(147, 'MA', 'Morocco'),
(148, 'MZ', 'Mozambique'),
(149, 'MM', 'Myanmar'),
(150, 'NA', 'Namibia'),
(151, 'NR', 'Nauru'),
(152, 'NP', 'Nepal'),
(153, 'NL', 'Netherlands'),
(154, 'AN', 'Netherlands Antilles'),
(155, 'NC', 'New Caledonia'),
(156, 'NZ', 'New Zealand'),
(157, 'NI', 'Nicaragua'),
(158, 'NE', 'Niger'),
(159, 'NG', 'Nigeria'),
(160, 'NU', 'Niue'),
(161, 'NF', 'Norfork Island'),
(162, 'MP', 'Northern Mariana Islands'),
(163, 'NO', 'Norway'),
(164, 'OM', 'Oman'),
(165, 'PK', 'Pakistan'),
(166, 'PW', 'Palau'),
(167, 'PA', 'Panama'),
(168, 'PG', 'Papua New Guinea'),
(169, 'PY', 'Paraguay'),
(170, 'PE', 'Peru'),
(171, 'PH', 'Philippines'),
(172, 'PN', 'Pitcairn'),
(173, 'PL', 'Poland'),
(174, 'PT', 'Portugal'),
(175, 'PR', 'Puerto Rico'),
(176, 'QA', 'Qatar'),
(177, 'RE', 'Reunion'),
(178, 'RO', 'Romania'),
(179, 'RU', 'Russian Federation'),
(180, 'RW', 'Rwanda'),
(181, 'KN', 'Saint Kitts and Nevis'),
(182, 'LC', 'Saint Lucia'),
(183, 'VC', 'Saint Vincent and the Grenadines'),
(184, 'WS', 'Samoa'),
(185, 'SM', 'San Marino'),
(186, 'ST', 'Sao Tome and Principe'),
(187, 'SA', 'Saudi Arabia'),
(188, 'SN', 'Senegal'),
(189, 'RS', 'Serbia'),
(190, 'SC', 'Seychelles'),
(191, 'SL', 'Sierra Leone'),
(192, 'SG', 'Singapore'),
(193, 'SK', 'Slovakia'),
(194, 'SI', 'Slovenia'),
(195, 'SB', 'Solomon Islands'),
(196, 'SO', 'Somalia'),
(197, 'ZA', 'South Africa'),
(198, 'GS', 'South Georgia South Sandwich Islands'),
(199, 'ES', 'Spain'),
(200, 'LK', 'Sri Lanka'),
(201, 'SH', 'St. Helena'),
(202, 'PM', 'St. Pierre and Miquelon'),
(203, 'SD', 'Sudan'),
(204, 'SR', 'Suriname'),
(205, 'SJ', 'Svalbarn and Jan Mayen Islands'),
(206, 'SZ', 'Swaziland'),
(207, 'SE', 'Sweden'),
(208, 'CH', 'Switzerland'),
(209, 'SY', 'Syrian Arab Republic'),
(210, 'TW', 'Taiwan'),
(211, 'TJ', 'Tajikistan'),
(212, 'TZ', 'Tanzania, United Republic of'),
(213, 'TH', 'Thailand'),
(214, 'TG', 'Togo'),
(215, 'TK', 'Tokelau'),
(216, 'TO', 'Tonga'),
(217, 'TT', 'Trinidad and Tobago'),
(218, 'TN', 'Tunisia'),
(219, 'TR', 'Turkey'),
(220, 'TM', 'Turkmenistan'),
(221, 'TC', 'Turks and Caicos Islands'),
(222, 'TV', 'Tuvalu'),
(223, 'UG', 'Uganda'),
(224, 'UA', 'Ukraine'),
(225, 'AE', 'United Arab Emirates'),
(226, 'GB', 'United Kingdom'),
(227, 'UM', 'United States minor outlying islands'),
(228, 'UY', 'Uruguay'),
(229, 'UZ', 'Uzbekistan'),
(230, 'VU', 'Vanuatu'),
(231, 'VA', 'Vatican City State'),
(232, 'VE', 'Venezuela'),
(233, 'VN', 'Vietnam'),
(234, 'VG', 'Virgin Islands (British)'),
(235, 'VI', 'Virgin Islands (U.S.)'),
(236, 'WF', 'Wallis and Futuna Islands'),
(237, 'EH', 'Western Sahara'),
(238, 'YE', 'Yemen'),
(239, 'YU', 'Yugoslavia'),
(240, 'ZR', 'Zaire'),
(241, 'ZM', 'Zambia'),
(242, 'ZW', 'Zimbabwe');

-- --------------------------------------------------------

--
-- Структура на таблица `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `active` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Схема на данните от таблица `language`
--

INSERT INTO `language` (`id`, `name`, `active`) VALUES
(1, 'en', 1),
(2, 'bg', 1),
(3, 'cn', 1),
(5, 'de', 1);

-- --------------------------------------------------------

--
-- Структура на таблица `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(200) DEFAULT NULL,
  `menuOrder` int(11) NOT NULL,
  `keywords` varchar(100) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `language` smallint(6) NOT NULL,
  `parent` int(11) NOT NULL,
  `menutype` smallint(6) NOT NULL,
  `footercolumn` int(11) NOT NULL,
  `menulink` varchar(255) DEFAULT NULL,
  `active` smallint(6) NOT NULL,
  `class` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Схема на данните от таблица `menu`
--

INSERT INTO `menu` (`id`, `caption`, `menuOrder`, `keywords`, `description`, `language`, `parent`, `menutype`, `footercolumn`, `menulink`, `active`, `class`) VALUES
(1, 'specialno test4e', 1, 'keyword, edno, dve', 'sssssss', 1, 0, 0, 1, 'specialno-test4e', 1, ''),
(3, 'About', 1, 'testk', 'testd', 1, 0, 0, 1, 'about', 1, 'fa-info'),
(4, 'Everything you need', 1, 'test', 'test', 1, 8, 1, 1, 'everything-you-need', 1, ''),
(5, 'Get high search ranking', 1, '', '', 1, 0, 1, 1, NULL, 1, NULL),
(6, 'Track of your positions', 1, '', '', 1, 0, 1, 1, 'track-of-your-positions', 1, NULL),
(7, 'Create impressive reports', 1, '', '', 1, 0, 1, 1, NULL, 1, NULL),
(8, 'Innovative technology', 1, '', '', 1, 3, 0, 1, 'innovative-technology', 1, NULL),
(9, 'Best local position', 1, '', '', 1, 0, 1, 1, 'best-local-position', 1, NULL),
(10, 'More traffic and clients', 1, '', '', 1, 0, 1, 1, NULL, 1, NULL),
(11, 'Why use seo street optimizer', 1, '', '', 1, 0, 1, 1, NULL, 1, NULL),
(12, 'Privacy Policy', 1, '', '', 1, 0, 3, 1, NULL, 1, NULL),
(13, 'Terms &amp; Conditions', 1, '', '', 1, 0, 3, 1, NULL, 1, NULL),
(14, 'Contact', 1, '', '', 1, 0, 3, 2, NULL, 1, NULL),
(15, 'Distributors', 1, '', '', 1, 0, 3, 2, NULL, 1, NULL),
(16, 'Reseller Club', 1, '', '', 1, 3, 0, 2, 'reseller club', 1, NULL),
(17, 'Brands', 1, '', '', 1, 0, 3, 3, NULL, 1, NULL),
(19, 'Specials', 1, '', '', 1, 0, 0, 3, 'specials', 1, NULL),
(20, 'Yahoo and Bing', 1, '', '', 1, 4, 2, 1, NULL, 1, NULL),
(23, 'teest caption', 1, 'dfg', 'sgsfgfsg', 1, 3, 0, 1, 'teest-caption', 1, NULL),
(26, 'fgsfgg', 1, 'sgsgs', 'gdg', 2, 0, 0, 1, 'fgsfg', 1, NULL);

-- --------------------------------------------------------

--
-- Структура на таблица `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура на таблица `resetpassword`
--

CREATE TABLE IF NOT EXISTS `resetpassword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `token` varchar(100) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Схема на данните от таблица `resetpassword`
--

INSERT INTO `resetpassword` (`id`, `user`, `token`, `date`, `ip`) VALUES
(1, 21, '9rdDUDKgyVx2Gr2MUjre3gv1RrjNJh2/PfLlt/2LQX1GUaGso0PFK4QPfmejZRPXJ/EVmH0qcJZQVDE4/uSCRMIZyP+Crt1Y97fT', '2015-11-06 10:00:28', '::1'),
(2, 21, 'ukZJ4deIxw6P8Ls0gIg0le+5dKlcllOIRytNsPY/hi5pM0UT0paQ9FgicxZa52qgqVyRnK0vOrJu5vNCz/U5OmrxTSSRN802Cmz8', '2015-11-06 10:02:49', '::1'),
(3, 21, 'KrhxwQ86FEDP96Cu3qYlJTIwTNIbwAvgIwQrCIiHqMhsajpEkW/wlZilOrwD+LkCS7QfYZ7zj03aVIGgzW0pLqc3ecEPrPtEYqET', '2015-11-06 10:05:03', '::1'),
(4, 21, '71gwsGCyLoUvpCjyhwQnvwCvXsbvdtW5XxSqXeUTHGes1LKg7ennzpWdd0C/cRg++qRgECKRwtbojE9YvZOxHIlY7IY/jm3yp9ED', '2015-11-06 10:05:20', '::1');

-- --------------------------------------------------------

--
-- Структура на таблица `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `password` varchar(72) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `birthDate` varchar(255) DEFAULT NULL,
  `lastLogin` varchar(255) DEFAULT NULL,
  `isDisabled` smallint(6) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `registered` varchar(255) DEFAULT NULL,
  `hideEmail` smallint(6) NOT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `admin` int(11) NOT NULL,
  `language` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Схема на данните от таблица `user`
--

INSERT INTO `user` (`id`, `name`, `surname`, `password`, `email`, `birthDate`, `lastLogin`, `isDisabled`, `image`, `registered`, `hideEmail`, `ip`, `admin`, `language`) VALUES
(1, 'Stanimir', 'Dimitrov', '$2y$12$Mko1YW9UYURWSjdPWWoyWeVSaGr1Dj4OA6X7rpea39ad0ZIXm/ke6', 'psyxopat@gmail.com', '0000-00-00', '2014-10-07 19:26:35', 0, '', '2014-09-23 21:08:31', 0, '127.0.0.1', 0, 1),
(3, 'Stanimir', '', '$2y$13$dGN1QWhZdFpTMlA4VHBGSeohj/4Lr38FnkVSbObs4fXOMJKuEF.OG', 'arasfgsgm@boza.be', '0000-00-00', '2014-12-07 09:05:52', 0, '', '2014-12-06 22:14:33', 0, '127.0.0.1', 0, 1),
(4, 'ivan', '', '$2y$13$SGxSTWM1R2xlVGVxMFFPUOWpiRnlUtiCsDQ4kK92I9YY0dek4tUBW', 'pek@gmail.com', '0000-00-00', '2014-12-07 11:06:17', 0, '', '2014-12-07 11:05:48', 0, '127.0.0.1', 1, 1),
(5, 'ivancho22222222', '', '$2y$13$ck1RZG5Ga0NWdFJKclZHde6QexNCWoAWeRY6OyxsZ3jQGvPhZeRTW', 'ppp@gmail.com', '0000-00-00', '2014-12-07 11:13:34', 0, '', '2014-12-07 11:13:11', 0, '127.0.0.1', 0, 1),
(6, 'Stanimir', '', '$2y$13$aXYwTE9ZQ29YZXBuWXhpMun1/KZf.On/r47yQoCgxB2D6QrFWSUKK', 'aaa@gmail.com', '0000-00-00', '2014-12-07 12:00:16', 0, '', '2014-12-07 11:59:53', 0, '127.0.0.1', 0, 1),
(7, 'Stanimir', '', '$2y$13$WXBVVXhsMFY2T0RQaVo0NeF7/9COOsHStAiTtln7Hq/VwxjNaa6dO', 'bbb@gmail.com', '0000-00-00', '2014-12-07 12:05:14', 0, '', '2014-12-07 12:04:35', 0, '127.0.0.1', 0, 1),
(18, 'StanimirCrypt', '', '$2y$13$0BALuGf4iK3WzpYo57naOOb1p/VPJBVYjSZ5IJhEf1CkLuWFV55bi', 'stanimirdim@gmail.com', '0000-00-00', '2015-06-08 11:33:23', 0, '', '2015-03-12 21:34:42', 0, '::1', 0, 1),
(19, 'Stanimir2', 'dimitrov', '', 'standim92@gmail.com', '0000-00-00', '0000-00-00 00:00:00', 0, '', '0000-00-00 00:00:00', 0, '', 1, 0),
(20, 'alert(String.fromCharCode(88,83,83))//";alert(String.fromCharCode(88,83,83))//-- "''alert(String.from', '', '$2y$13$OJ.Juvg5KSZnwO0PCEYyc./qYMR4hcoW7XZq9.RZJHocZqvVwMun.', 'sgsfgsfxh@gmail.com', '0000-00-00', '0000-00-00 00:00:00', 1, '', '2015-04-13 18:19:00', 0, '127.0.0.1', 0, 1),
(21, 'Stanimir', 'Dimitrov', '$2y$13$ob9xD1VcLpJxu8mdnQXOLuieF7RKMww3MsAfZlnlHznoquMUDFlci', 'stanimirdim92@gmail.com', '1992-03-06', '2015-11-16 12:50:16', 0, '21.jpg', '2015-06-27 17:31:27', 0, '::1', 0, 1),
(22, 'mack', '', '$2y$13$vbwB7mYjbIC7qEj10BACEODhFklIlVFFZiYabtXObKs0ax3lss/g6', 'mack@gmail.com', '0000-00-00', '2015-09-10 19:37:37', 0, '', '2015-09-10 19:37:24', 0, '::1', 0, 1),
(23, 'mitko', '', '$2y$13$i/vgN8t2Aax6p1btko2vU.Sw7ffs59dIlTHKR9owLOcPZMPCrRDrC', 'mitkodim@gmail.com', '0000-00-00', '2015-10-10 17:19:25', 0, '', '2015-10-10 17:19:02', 0, '::1', 0, 1),
(24, 'stan4o', NULL, '$2y$13$dm/pMvRhVMEls8ymQHfOoeMBvhGhvz7NroijFjVI8REJcyuoQhzJG', 'stan4o123@gmail.com', '0000-00-00', '0000-00-00 00:00:00', 0, NULL, '2015-11-02 11:47:31', 0, '::1', 0, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
