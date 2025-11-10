create table if not exists  `b_sh_banner_rules` (
  `ID` int(18) NOT NULL AUTO_INCREMENT,
  `PROFILE_ID` int(11) NOT NULL,
  `VALUE` varchar(255) NOT NULL,
  `VALUE_INT` int(18) NOT NULL,
  `PARAM` varchar(255) NOT NULL,
  `PARAM_INT` varchar(255) NOT NULL,
  `CLASS` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
);

create table if not exists  `b_sh_banner_profiles` (
  `ID` int(18) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) NOT NULL,
  `SORT` int(18) NOT NULL,
  PRIMARY KEY (`ID`)
);

create table if not exists  `b_sh_banner_banner_profiles` (
  `ID` int(18) NOT NULL AUTO_INCREMENT,
  `BANNER_ID` int(18) NOT NULL,
  `PROFILE_ID` int(18) NOT NULL,
  PRIMARY KEY (`ID`)
);
