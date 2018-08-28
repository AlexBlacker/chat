-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u4
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 27 2018 г., 13:13
-- Версия сервера: 5.5.49
-- Версия PHP: 5.6.33-1~dotdeb+7.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `chat`
--

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `mess` text NOT NULL,
  `attach` varchar(255) NOT NULL,
  `timesend` datetime NOT NULL,
  `unread` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2430 ;

-- --------------------------------------------------------

--
-- Структура таблицы `smiles`
--

CREATE TABLE IF NOT EXISTS `smiles` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `smile` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

INSERT INTO `smiles` (`id`, `smile`, `img`) VALUES
(1, '(smile)', '<img src="/assets/img/smiles/smile_80_anim_gif.gif" width="40px">'),
(2, '(bigsmile)', '<img src="/assets/img/smiles/bigsmile_80_anim_gif.gif" width="40px">'),
(3, '(rofl)', '<img src="/assets/img/smiles/rofl_80_anim_gif.gif" width="40px">'),
(4, '(cryingwhilelaughing)', '<img src="/assets/img/smiles/cryingwhilelaughing_80_anim_gif.gif" width="40px">'),
(5, '(giggle)', '<img src="/assets/img/smiles/giggle_80_anim_gif.gif" width="40px">'),
(6, '(tongueout)', '<img src="/assets/img/smiles/tongueout_80_anim_gif.gif" width="40px">'),
(7, '(angel)', '<img src="/assets/img/smiles/angel_80_anim_gif.gif" width="40px">'),
(8, '(nod)', '<img src="/assets/img/smiles/nod_80_anim_gif.gif" width="40px">'),
(9, '(heart)', '<img src="/assets/img/smiles/heart_80_anim_gif.gif" width="40px">'),
(10, '(hug)', '<img src="/assets/img/smiles/hug_80_anim_gif.gif" width="40px">'),
(11, '(inlove)', '<img src="/assets/img/smiles/inlove_80_anim_gif.gif" width="40px">'),
(12, '(kiss)', '<img src="/assets/img/smiles/kiss_80_anim_gif.gif" width="40px">'),
(13, '(lips)', '<img src="/assets/img/smiles/lips_80_anim_gif.gif" width="40px">'),
(14, '(mmm)', '<img src="/assets/img/smiles/mmm_80_anim_gif.gif" width="40px">'),
(15, '(blushing)', '<img src="/assets/img/smiles/blushing_80_anim_gif.gif" width="40px">'),
(16, '(party)', '<img src="/assets/img/smiles/party_80_anim_gif.gif" width="40px">'),
(17, '(sadsmile)', '<img src="/assets/img/smiles/sadsmile_80_anim_gif.gif" width="40px">'),
(18, '(crying)', '<img src="/assets/img/smiles/crying_80_anim_gif.gif" width="40px">'),
(19, '(worried)', '<img src="/assets/img/smiles/worried_80_anim_gif.gif" width="40px">'),
(20, '(cool)', '<img src="/assets/img/smiles/cool_80_anim_gif.gif" width="40px">'),
(21, '(nerd)', '<img src="/assets/img/smiles/nerd_80_anim_gif.gif" width="40px">'),
(22, '(surprised)', '<img src="/assets/img/smiles/surprised_80_anim_gif.gif" width="40px">'),
(23, '(doh)', '<img src="/assets/img/smiles/doh_80_anim_gif.gif" width="40px">'),
(24, '(facepalm)', '<img src="/assets/img/smiles/facepalm_80_anim_gif.gif" width="40px">'),
(25, '(headbang)', '<img src="/assets/img/smiles/headbang_80_anim_gif.gif" width="40px">'),
(26, '(donttalktome)', '<img src="/assets/img/smiles/donttalktome_80_anim_gif.gif" width="40px">'),
(27, '(wondering)', '<img src="/assets/img/smiles/wondering_80_anim_gif.gif" width="40px">'),
(28, '(yawning)', '<img src="/assets/img/smiles/yawning_80_anim_gif.gif" width="40px">');

-- --------------------------------------------------------

--
-- Структура таблицы `smilestat`
--

CREATE TABLE IF NOT EXISTS `smilestat` (
  `smile` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL COMMENT 'md5 pass',
  `online` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `users` (`id`, `name`, `pass`, `online`, `avatar`, `token`) VALUES
(1, 'user1', 'b59c67bf196a4758191e42f76670ceba', '1509003941', '', ''),
(2, 'user2', 'b59c67bf196a4758191e42f76670ceba', '1477134284', '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
