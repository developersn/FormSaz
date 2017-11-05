<?php
/**
 * Jalali Calendar : Persian date class.
 * 	Including: date, getdate, mktime function in Jalali
 *
 * Author : Amir Hossein Hodjaty Pour (AHHP) ~ Boplo.ir
 * Date conversion : haghparast at google mail dot com
 * License : GPL
 * Version 2.0.0
 * First release 1387/12/24 02:02 am
 * Last update 1389/08/02 11:00 am
 * 
 * Examples
 * <code>
 * 	$c = new jCalendar;
 * 	echo $c->date('j F'); // ۶ آبان
 * 	$date = $c->getdate();
 * 	print_r($date, true);
 * 	$timestamp = $c->mktime(19, 45, 30, 7, 23, 1366);
 * 	echo date('j F', $timestamp); // 15 October
 * 	echo $c->date('j F', $timestamp); // ۲۳ مهر
 * 	$c->farsiDigits = false; // ۲۳ مهر
 * 	echo $c->date('j F', $timestamp); // 23 مهر
 * </code>
*/
Class jCalendar {
	/**
	 * Assoc array, container of all date chars
	*/
	public $format = array();
	/**
	 * Assoc array, container of all jdate chars
	*/
	public $jformat = array();
	/**
	 * Use Farsi digits on output.
	*/
	public $farsiDigits = false;
	
	
	
	/**
	 * PHP::getdate() in Jalali result
	 *
	 * @param $timestamp int  Unix Timestamp
	 * @return array Date data on standard getdate result array
	*/
	public function getdate($timestamp=null) {
		$timestamp = $timestamp===null ? time() : $timestamp;
		$this->jdate('', $timestamp);
		$getDate = getdate($timestamp);
		$getDate['mday'] = $this->jformat['j']; $getDate['wday'] = $this->jformat['w'];
		$getDate['mon'] = $this->jformat['n']; $getDate['year'] = $this->jformat['Y'];
		$getDate['yday'] = $this->jformat['z']; $getDate['weekday'] = $this->jformat['l'];
		$getDate['month'] = $this->jformat['F'];
		foreach($getDate as $key => $value) $getDate[$key] = $this->translateDigits($value);
		$getDate[0] = $timestamp;
		return $getDate;
	}
	
	
	
	/**
	 * PHP::mktime() by Jalali inputs
	 *
	 * @param $hour int  Standard hour
	 * @param $minute int  Standard minute
	 * @param $second int  Standard second
	 * @param $month int  Standard Jalali month
	 * @param $day int  Standard Jalali day number
	 * @param $year int  Standard Jalali year
	 * @return int Unix Timestamp (GREGORIAN)
	*/
	public function mktime($hour=null, $minute=null, $second=null, $month=null, $day=null, $year=null) {
		$this->jdate('Y');
		$year = $year===null ? $this->jformat['Y'] : $year;
		$month = $month===null ? $this->jformat['n'] : $month;
		$day = $day===null ? $this->jformat['j'] : $day;
		$hour = $hour===null ? $this->jformat['h'] : $hour;
		$minute = $minute===null ? $this->jformat['i'] : $minute;
		$second = $second===null ? $this->jformat['s'] : $second;
		list($year, $month, $day) = $this->jalali_to_gregorian($year, $month, $day);
		return mktime($hour, $minute, $second, $month, $day, $year);
	}
	
	
	
	/**
	 * PHP::date() by Jalali result
	 *
	 * @param $format string  Standard PHP::date() format
	 * @param $stamp int  Unix Timestamp
	 * @param $GMT int  Difference to server time in seconds
	 * @return string Converted input
	*/
	public function date($format, $stamp=null, $GMT=null) {
		$GMT = isset($GMT) ? $GMT : date("Z");
		$stamp = isset($stamp) ? ($stamp+$GMT) : (time()+$GMT);
		
		$formatArr = array(
			'd', 'D', 'j', 'l', 'N', 'S', 'w', 'z', 'W', 'F', 'm', 'M', 'n', 't', 'L', 'o', 'Y', 'y',
			'a','A', 'B', 'g', 'G', 'h', 'H', 'i', 's', 'u', 'e', 'I', 'O', 'P', 'T', 'Z', 'c', 'r', 'U'
		);
		$fullFormat = explode("|", date(join("|", $formatArr), $stamp) );
		$count = count($formatArr);
		for($i=0; $i<$count; $i++) $this->format[$formatArr[$i]] = $fullFormat[$i];
		
		// Y
		list($this->jformat['Y'], $this->jformat['m'], $this->jformat['d']) = $this->gregorian_to_jalali($this->format['Y'], $this->format['m'], $this->format['d']);
		// a
		$this->jformat['a'] = ($this->format['a']=="pm") ? "ب.ظ" : "ق.ظ";
		// A
		$this->jformat['A'] = ($this->format['A']=="PM") ? "بعد از ظهر" : "قبل از ظهر";
		// B
		$this->jformat['B'] = $this->format['B'];
		// D
		switch ( strtolower($this->format['D']) ) {
			case "sat" : $this->jformat['D'] = "ش"; break; case "sun" : $this->jformat['D'] = "ی"; break;
			case "mon" : $this->jformat['D'] = "د"; break; case "tue" : $this->jformat['D'] = "س"; break;
			case "wed" : $this->jformat['D'] = "چ"; break; case "thu" : $this->jformat['D'] = "پ"; break;
			case "fri" : $this->jformat['D'] = "ج"; break;
		}
		// F
		$this->jformat['F'] = $this->ReturnMonthName($this->jformat['m']);
		// h
		$this->jformat['h'] = $this->format['h'];
		// H
		$this->jformat['H'] = $this->format['H'];
		// g
		$this->jformat['g'] = $this->format['g'];
		// G
		$this->jformat['G'] = $this->format['G'];
		// i
		$this->jformat['i'] = $this->format['i'];
		// j
		$this->jformat['j'] = $this->jformat['d'];
		// d
		$this->jformat['d'] = ($this->jformat['d'] < 10) ? "0".$this->jformat['d'] : $this->jformat['d'];
		// l
		switch ( strtolower($this->format['l']) ) {
			case "saturday" : $this->jformat['l'] = "شنبه"; break;
			case "sunday" : $this->jformat['l'] = "یکشنبه"; break;
			case "monday" : $this->jformat['l'] = "دوشنبه"; break;
			case "tuesday" : $this->jformat['l'] = "سه شنبه"; break;
			case "wednesday" : $this->jformat['l'] = "چهارشنبه"; break;
			case "thursday" : $this->jformat['l'] = "پنجشنبه"; break;
			case "friday" : $this->jformat['l'] = "جمعه"; break;
		}
		// L
		$ka = date("L", (time()-31536000)); // previous Gregorian year
		$this->jformat['L'] = ($ka==1) ? 1 : 0;
		// n
		$this->jformat['n'] = $this->jformat['m'];
		// m
		$this->jformat['m'] = ($this->jformat['m'] < 10) ? "0".$this->jformat['m'] : $this->jformat['m'];
		// M
		$this->jformat['M'] = $this->jformat['F'];
		// N
		switch ( strtolower($this->format['l']) ) {
			case "saturday" : $this->jformat['N'] = 1; break;
			case "sunday" : $this->jformat['N'] = 2; break;
			case "monday" : $this->jformat['N'] = 3; break;
			case "tuesday" : $this->jformat['N'] = 4; break;
			case "wednesday" : $this->jformat['N'] = 5; break;
			case "thursday" : $this->jformat['N'] = 6; break;
			case "friday" : $this->jformat['N'] = 7; break;
		}
		// o
		$this->jformat['o'] = $this->jformat['Y'];
		// w
		$this->jformat['w'] = $this->jformat['N'] - 1;
		// t
		$this->jformat['t'] = ($this->jformat['m'] <= 6) ? 31 : 30;
		$this->jformat['t'] = ($this->jformat['m'] == 12) ? ($this->jformat['L'] == 1 ? 30 : 29) : $this->jformat['t'];
		// s
		$this->jformat['s'] = $this->format['s'];
		// S
		$this->jformat['S'] = "ام";
		// e
		$this->jformat['e'] = $this->format['e'];
		// I
		$this->jformat['I'] =$this->format['I'];
		// u
		$this->jformat['u'] = $this->format['u'];
		// U
		$this->jformat['U'] = $this->format['U'];
		// y
		$this->jformat['y'] = $this->jformat['Y']%100;
		// Z
		$this->jformat['Z'] = $this->format['Z'];
		// z
		if($this->jformat['n'] > 6) $this->jformat['z'] = 186 + (($this->jformat['n'] - 6 - 1) * 30) + $this->jformat['j'];
		else $this->jformat['z'] = (($this->jformat['n'] - 1) * 31) + $this->jformat['j'];
		// W
		$this->jformat['W'] = is_integer($this->jformat['z'] / 7) ? ($this->jformat['z'] / 7) : ($this->jformat['z'] / 7 + 1);
		// r
		$positive_z = abs(($this->jformat['Z'])/3600);
		if($positive_z > 1) {
			$z_hour = ((int) $positive_z < 10 ? "0" : "") . (int) $positive_z;
			$z_minute = (($positive_z - $z_hour) * 60 < 10 ? "0" : "") . ($positive_z - $z_hour) * 60;
		} else {
			$z_hour = "00";
			$z_minute = (($positive_z) * 60 < 10 ? "0" : "") . ($positive_z) * 60;
		}
		// P
		$this->jformat['P'] = ($this->jformat['Z'] >= 0 ? "+" : "-") . "$z_hour:$z_minute";
		// O
		$this->jformat['O'] = ($this->jformat['Z'] >= 0 ? "+" : "-") . $z_hour . $z_minute;
		// c
		$this->jformat['c'] = $this->jformat['Y'] ."-". $this->jformat['m'] ."-". $this->jformat['d'] ."-". $this->jformat['H'] ." ". $this->jformat['i'] . ":" . $this->jformat['s']. $this->jformat['P'];
		// r
		$this->jformat['r'] = $this->jformat['l'] ." ". $this->jformat['j'] ." ". $this->jformat['F'] ." ". $this->jformat['Y'] ." ". $this->jformat['h'] . ":" . $this->jformat['i'] . ":" . $this->jformat['s'] ." ". $this->jformat['O'];		
		// T
		$this->jformat['T'] = $this->format['T'];
		foreach($formatArr as $key) $format = str_replace($key, $this->jformat[$key], $format);
		return $this->translateDigits($format);
	}
	
	
	
	/**
	 * Built-in methods
	*/
	// @ changed by rezaonline.net
	private function ReturnMonthName($mon=0)
	{
		$month = array('','فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند');
		return $month[intval($mon)];
	}
	
	private function div($a,$b) { return (int) ($a / $b); }
	private function translateDigits($str) {
		if($this->farsiDigits !== true) return $str;
		return str_replace(array('0','1','2','3','4','5','6','7','8','9'), array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'), $str);
	}
	
	/**
	 * Conversion methods
	 * Thanks to Roozbeh Pournader and Mohammad Toosi for their Date Conversion program
	*/
	public function gregorian_to_jalali ($g_y, $g_m, $g_d) {
		$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
		$gy = $g_y-1600; $gm = $g_m-1; $gd = $g_d-1;
		$g_day_no = 365*$gy+$this->div($gy+3,4)-$this->div($gy+99,100)+$this->div($gy+399,400);
		for ($i=0; $i < $gm; ++$i) $g_day_no += $g_days_in_month[$i];
		if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0))) $g_day_no++; /* leap and after Feb */
		$g_day_no += $gd; $j_day_no = $g_day_no-79; $j_np = $this->div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */
		$j_day_no = $j_day_no % 12053; $jy = 979+33*$j_np+4*$this->div($j_day_no,1461); /* 1461 = 365*4 + 4/4 */
		$j_day_no %= 1461; if($j_day_no >= 366) { $jy += $this->div($j_day_no-1, 365); $j_day_no = ($j_day_no-1)%365; }
		for($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i) $j_day_no -= $j_days_in_month[$i];
		$jm = $i+1; $jd = $j_day_no+1;
		return array($jy, $jm, $jd);
	}
	public function jalali_to_gregorian($j_y, $j_m, $j_d) {
		$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
		$jy = $j_y-979; $jm = $j_m-1; $jd = $j_d-1;
		$j_day_no = 365*$jy + $this->div($jy, 33)*8 + $this->div($jy%33+3, 4);
		for ($i=0; $i < $jm; ++$i) $j_day_no += $j_days_in_month[$i];
		$j_day_no += $jd; $g_day_no = $j_day_no+79; 
		$gy = 1600 + 400*$this->div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
		$g_day_no = $g_day_no % 146097; $leap = true;
		if ($g_day_no >= 36525) { /* 36525 = 365*100 + 100/4 */
			$g_day_no--; $gy += 100*$this->div($g_day_no, 36524); /* 36524 = 365*100 + 100/4 - 100/100 */
			$g_day_no = $g_day_no % 36524; if ($g_day_no >= 365) $g_day_no++; else $leap = false;
		}
		$gy += 4*$this->div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */ $g_day_no %= 1461;
		if ($g_day_no >= 366) {
			$leap = false; $g_day_no--; $gy += $this->div($g_day_no, 365); $g_day_no = $g_day_no % 365;
		}
		for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++) $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
		$gm = $i+1; $gd = $g_day_no+1;
		return array($gy, $gm, $gd);
	}
	
	/**
	 * Backward compatibility
	*/
	public function jdate($format, $stamp=null, $GMT=null) {
		return $this->date($format, $stamp, $GMT);
	}
}


/*
*	singleton jdate class
* 	 by rezaonline.net
*/
class Jdate extends jCalendar{
	
	static private $init = NULL;
	
	static public function init()
	{
		if(is_null(self::$init))
			self::$init = new self;
		
		return self::$init;
	}

}

@date_default_timezone_set('Asia/Tehran'); 

## added by rezaonline.net
// @ change to p date
	
	function jdate($pattern ='j F Y' , $timestamp = null)
	{
		if($timestamp ===null) $timestamp = time() ;
		return pdate($pattern , intval($timestamp));	
	}
	
## added by rezaonline.net	
// @ change to p date
	function jmktime($hour= 0, $minute= 0, $second= 0, $month= 0, $day= 0, $year= 0, $is_dst= -1)
	{
		if(($hour == 0) && ($minute == 0) && ($second == 0) && ($month == 0) && ($day == 0) && ($year == 0))
			return time();
		return pmktime($hour, $minute, $second, $month, $day, $year);
	}
	
# Copyright (C) 1388-1391 / 2009-2012 Vahid Sohrablou (IranPHP.org) 
# 
# This program is free software; you can redistribute it and/or 
# modify it under the terms of the GNU General Public License 
# as published by the Free Software Foundation; either version 2 
# of the License, or (at your option) any later version. 
# 
# This program is distributed in the hope that it will be useful, 
# but WITHOUT ANY WARRANTY; without even the implied warranty of 
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
# GNU General Public License for more details. 
# 
# A copy of the GNU General Public License is available from: 
# 
# <a href="http://gnu.org/copyleft/gpl.html" target="_blank">http://gnu.org/copyleft/gpl.html</a> 

# Version 1.2.9	
	
function pdate($format, $timestamp = NULL)
{
	static $pdate_month_name = array('', 'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند');static $pdate_week_name = array('شنبه', 'یکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنج شنبه', 'جمعه');static $pdate_month_days = array(0, 31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

	if(!$timestamp)
	{
		$timestamp = time();
	}

	# Create need date parametrs
	list($gYear, $gMonth, $gDay, $gWeek) = explode ('-', date('Y-m-d-w', $timestamp));
	list($pYear, $pMonth, $pDay) = gregorian_to_jalali($gYear, $gMonth, $gDay);
	$pWeek = ($gWeek + 1);

	if($pWeek >= 7)
	{
		$pWeek = 0;
	}

	if($format == '\\')
	{
		$format = '//';
	}

	$lenghFormat = strlen($format);
	$i = 0;
	$result = '';

	while($i < $lenghFormat)
	{
		$par = $format{$i};
		if($par == '\\')
		{
			$result .= $format{++ $i};
			$i ++;
			continue;
		}
		switch($par)
		{
			# Day
			case 'd':
				$result .= (($pDay < 10) ? ('0' . $pDay) : $pDay);
			break;

			case 'D':
				$result .= substr($pdate_week_name[$pWeek], 0, 2);
			break;

			case 'j':
				$result .= $pDay;
			break;

			case 'l':
				$result .= $pdate_week_name[$pWeek];
			break;

			case 'N':
				$result .= ($pWeek + 1);
			break;

			case 'w':
				$result .= $pWeek;
			break;

			case 'z':
				$result .= DayOfYear($pMonth, $pDay);
			break;

			case 'S':
				$result .= 'م';
			break;

			# Week
			case 'W':
				$result .= ceil(DayOfYear($pMonth, $pDay) / 7);
			break;

			# Month
			case 'F':
				$result .= $pdate_month_name[$pMonth];
			break;

			case 'm':
				$result .= (($pMonth < 10) ? ('0' . $pMonth) : $pMonth);
			break;

			case 'M':
				$result .= $pdate_month_name[intval($pMonth)];
			break;

			case 'n':
				$result .= $pMonth;
			break;

			case 't':
				$result .= ((isKabise($pYear) && $pMonth == 12) ? 30 : $pdate_month_days[$pMonth]);
			break;

			# Years
			case 'L':
				$result .= (int)isKabise($pYear);
			break;

			case 'Y':
			case 'o':
				$result .= $pYear;
			break;

			case 'y':
				$result .= substr($pYear, 2);
			break;

			# Time
			case 'a':
			case 'A':
				if(date('a', $timestamp) == 'am')
				{
					$result .= (($par == 'a') ? '.ق.ظ' : 'قبل از ظهر');
				}
				else
				{
					$result .= (($par == 'a') ? '.ب.ظ':'بعد از ظهر');
				}
			break;

			case 'B':
			case 'g':
			case 'G':
			case 'h':
			case 'H':
			case 's':
			case 'u':
			case 'i':
			# Timezone
			case 'e':
			case 'I':
			case 'O':
			case 'P':
			case 'T':
			case 'Z':
				$result .= date($par, $timestamp);
			break;

			# Full Date/Time
			case 'c':
				$result .= ($pYear . '-' . $pMonth . '-' . $pDay . 'T' . date('H::i:sP', $timestamp));
			break;

			case 'r':
				$result .= (substr($pdate_week_name[$pWeek], 0, 2) . '، ' . $pDay . ' ' . substr($pdate_month_name[$pMonth], 0, 6) . ' ' . $pYear . ' ' . date('H::i:s P', $timestamp));
			break;

			case 'U':
				$result .= $timestamp;
			break;

			default:
				$result .= $par;
		}
		$i ++;
	}

	return $result;
}

function pstrftime($format, $timestamp = NULL)
{
	static $pdate_month_name = array('', 'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند');static $pdate_week_name = array('شنبه', 'یکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنج شنبه', 'جمعه');static $pdate_month_days = array(0, 31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

	if(!$timestamp)
	{
		$timestamp= time();
	}

	# Create need date parametrs
	list($gYear, $gMonth, $gDay, $gWeek)= explode ('-', date('Y-m-d-w', $timestamp));
	list($pYear, $pMonth, $pDay)= gregorian_to_jalali($gYear, $gMonth, $gDay);
	$pWeek = ($gWeek + 1);
	
	if($pWeek >= 7)
	{
		$pWeek = 0;
	}

	$lenghFormat = strlen($format);
	$i = 0;
	$result = '';

	while($i < $lenghFormat)
	{
		$par = $format{$i};
		if($par == '%')
		{
			$type= $format{++ $i};
			switch($type)
			{
				# Day
				case 'a':
					$result .= substr($pdate_week_name[$pWeek], 0, 2);
				break;

				case 'A':
					$result .= $pdate_week_name[$pWeek];
				break;

				case 'd':
					$result .= (($pDay < 10) ? ('0' . $pDay) : $pDay);
				break;

				case 'e':
					$result.= $pDay;
				break;

				case 'j':
					$dayinM = DayOfYear($pMonth, $pDay);
					$result .= (($dayinM < 10) ? ('00' . $dayinM) : (($dayinM < 100) ? ('0' . $dayinM) : $dayinM));
				break;

				case 'u':
					$result .= ($pWeek + 1);
				break;

				case 'w':
					$result .= $pWeek;
				break;

				# Week
				case 'U':
					$result .= floor(DayOfYear($pMonth, $pDay) / 7);
				break;

				case 'V':
				case 'W':
					$result .= ceil(DayOfYear($pMonth, $pDay) / 7);
				break;

				# Month
				case 'b':
				case 'h':
					$result .= substr($pdate_month_name[$pMonth], 0, 6);
				break;

				case 'B':
					$result .= $pdate_month_name[$pMonth];
				break;

				case 'm':
					$result .= (($pMonth < 10) ? ('0' . $pMonth) : $pMonth);
				break;

				# Year
				case 'C':
					$result .= ceil($pYear / 100);
				break;

				case 'g':
				case 'y':
					$result .= substr($pYear, 2);
				break;

				case 'G':
				case 'Y':
					$result .= $pYear;
				break;

				# Time
				case 'H':
				case 'I':
				case 'l':
				case 'M':
				case 'R':
				case 'S':
				case 'T':
				case 'X':
				case 'z':
				case 'Z':
					$result .= strftime('%' . $type, $timestamp);
				break;

				case 'p':
				case 'P':
				case 'r':
					if(date('a', $timestamp) == 'am')
					{
						$result .= (($type == 'p') ? 'ق.ظ' : (($type == 'P') ? 'قبل از ظهر' : strftime("%I:%M:%S قبل از ظهر", $timestamp)));
					}
					else{
						$result .= (($type == 'p') ? 'ب.ظ' : (($type == 'P') ? 'بعد از ظهر' : strftime("%I:%M:%S بعد از ظهر", $timestamp)));
					}
				break;
				
				# Time and Date Stamps
				case 'c':
					$result .= (substr($pdate_week_name[$pWeek], 0, 2) . ' ' . substr($pdate_month_name[$pMonth], 0, 6) . ' ' . $pDay . ' ' . strftime("%T", $timestamp) . ' ' . $pYear);
				break;
				
				case 'D':
				case 'x':
					$result .= (($pMonth < 10) ? '0' . $pMonth : $pMonth) . '/' . (($pDay < 10) ? '0' . $pDay : $pDay) . '/' . substr($pYear, 2);
				break;

				case 'F':
					$result .= ($pYear . '-' . (($pMonth < 10) ? '0' . $pMonth:$pMonth) . '-' . (($pDay < 10) ? '0' . $pDay : $pDay));
				break;

				case 's':
					$result .= $timestamp;
				break;

				# Miscellaneous
				case 'n':
					$result .= "\n";
				break;

				case 't':
					$result .= "\t";
				break;

				case '%':
					$result .= '%';
				break;

				default:
					$result .= ('%' . $type);
			}
		}
		else
		{
			$result .= $par;
		}
		$i ++;
	}

	return $result;
}

function DayOfYear($pMonth, $pDay)
{
	global $pdate_month_days;
	$days = 0;

	for($i = 1; $i < $pMonth; $i ++)
	{
		$days += $pdate_month_days[$i];
	}

	return ($days + $pDay);
}

function isKabise($year)
{
	$mod = ($year % 33);

	if(($mod == 1) or ($mod == 5) or ($mod == 9) or ($mod == 13) or ($mod == 17) or ($mod == 22) or ($mod == 26) or ($mod == 30))
	{
		return 1;
	}

	return 0;
}

function pmktime($hour = 0, $minute = 0, $second = 0, $month = 0, $day = 0, $year = 0, $is_dst = -1)
{
	if(($hour == 0) && ($minute == 0) && ($second == 0) && ($month == 0) && ($day == 0) && ($year == 0))
	{
		return time();
	}

	list($year, $month, $day) = jalali_to_gregorian($year, $month, $day);
	return mktime($hour, $minute, $second, $month, $day, $year, $is_dst);
}

function pcheckdate($month, $day, $year)
{
	if(($month < 1) || ($month > 12) || ($year < 1) || ($year > 32767) || ($day < 1))
	{
		return 0;
	}

	global $pdate_month_days;

	if($day > $pdate_month_days[$month])
	{
		if(($month != 12) || ($day != 30) || !isKabise($year))
		{
			return 0;
		}
	}

	return 1;
}

function pgetdate($timestamp = NULL)
{
	if(!$timestamp)
	{
		$timestamp = mktime();
	}

	list($seconds, $minutes, $hours, $mday, $wday, $mon, $year, $yday, $weekday, $month) = explode('-', pdate('s-i-G-j-w-n-Y-z-l-F', $timestamp));
	return array(0=>$timestamp, 'seconds'=>$seconds, 'minutes'=>$minutes, 'hours'=>$hours, 'mday'=>$mday, 'wday'=>$wday, 'mon'=>$mon, 'year'=>$year, 'yday'=>$yday, 'weekday'=>$weekday, 'month'=>$month);
}

# Copyright (C) 2000 Roozbeh Pournader and Mohammad Tou'si 
# 
# This program is free software; you can redistribute it and/or 
# modify it under the terms of the GNU General Public License 
# as published by the Free Software Foundation; either version 2 
# of the License, or (at your option) any later version. 
# 
# This program is distributed in the hope that it will be useful, 
# but WITHOUT ANY WARRANTY; without even the implied warranty of 
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
# GNU General Public License for more details. 
# 
# A copy of the GNU General Public License is available from: 
# 
# <a href="http://gnu.org/copyleft/gpl.html" target="_blank">http://gnu.org/copyleft/gpl.html</a> 

function div($a, $b)
{
	return (int)($a / $b);
}

function gregorian_to_jalali ($g_y, $g_m, $g_d)
{
	static $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	static $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
	$gy = ($g_y - 1600);
	$gm = ($g_m - 1);
	$gd = ($g_d - 1);
	$g_day_no = (365 * $gy + div($gy + 3, 4) - div($gy + 99, 100) + div($gy + 399, 400));

	for($i = 0; $i < $gm; ++ $i)
	{
		$g_day_no += $g_days_in_month[$i];
	}

		if($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0)))
	# leap and after Feb
	$g_day_no ++;
	$g_day_no += $gd;
	$j_day_no = ($g_day_no - 79);
	$j_np = div($j_day_no, 12053); # 12053 = (365 * 33 + 32 / 4)
	$j_day_no = ($j_day_no % 12053);
	$jy = (979 + 33 * $j_np + 4 * div($j_day_no, 1461)); # 1461 = (365 * 4 + 4 / 4)
	$j_day_no %= 1461;

	if($j_day_no >= 366)
	{ 
		$jy += div($j_day_no - 1, 365);
		$j_day_no = (($j_day_no - 1) % 365);
	} 

	for($i = 0; ($i < 11 && $j_day_no >= $j_days_in_month[$i]); ++ $i)
	{
		$j_day_no -= $j_days_in_month[$i];
	}

	$jm = ($i + 1);
	$jd = ($j_day_no + 1);
	return array($jy, $jm, $jd);
}

function jalali_to_gregorian($j_y, $j_m, $j_d)
{
	static $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	static $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
	$jy = ($j_y - 979);
	$jm = ($j_m - 1);
	$jd = ($j_d - 1);
	$j_day_no = (365 * $jy + div($jy, 33) * 8 + div($jy % 33 + 3, 4));

	for($i = 0; $i < $jm; ++ $i)
	{
		$j_day_no += $j_days_in_month[$i];
	}

	$j_day_no += $jd;
	$g_day_no= ($j_day_no + 79);
	$gy = (1600 + 400 * div($g_day_no, 146097)); # 146097 = (365 * 400 + 400 / 4 - 400 / 100 + 400 / 400)
	$g_day_no= ($g_day_no % 146097);
	$leap = 1;

	if($g_day_no >= 36525) # 36525 = (365 * 100 + 100 / 4)
	{
		$g_day_no --;
		$gy += (100 * div($g_day_no, 36524)); # 36524 = (365 * 100 + 100 / 4 - 100 / 100)
		$g_day_no= ($g_day_no % 36524);
		if($g_day_no >= 365)
		{
			$g_day_no ++;
		}
		else
		{
			$leap = 0;
		}
	}

	$gy += (4 * div($g_day_no, 1461)); # 1461 = (365 * 4 + 4 / 4)
	$g_day_no %= 1461;

	if($g_day_no >= 366)
	{
		$leap = 0;
		$g_day_no --;
		$gy += div($g_day_no, 365);
		$g_day_no = ($g_day_no % 365);
	}

	for($i = 0; ($g_day_no >= ($g_days_in_month[$i] + ($i == 1 && $leap))); $i ++)
	{
		$g_day_no -= ($g_days_in_month[$i] + ($i == 1 && $leap));
	}

	$gm = ($i + 1);
	$gd = ($g_day_no + 1);
	return array($gy, $gm, $gd);
}

	
