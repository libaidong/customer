EpocTools - PHP epoc manipulation
=================================
A simple library to provide various epoc handling functionality.


Installation
============

Installing into CodeIgniter
---------------------------
Download this repo and copy into your application directory.

Alternatively, install with [Composer](http://getcomposer.org).


Functions
=========

`age` - Helper function for `humanize` that returns the age of something
------------------------------------------------------------------------
This function is used to calculate the age of an item relative to another time. See `humanize` for a full example list

	$this->EpocTools = new EpocTools();
	$this->EpocTools->Age(strtotime('2011-01-01'), strtotime('2011-01-02')) // Reutns '1 day'
	$this->EpocTools->Age(strtotime('2011-01-01 22:00'), strtotime('2011-01-02 23:00')) // Reutns '1 day, 1 hour'


`date` - Return the time at midnight of an epoc
---------------------------------------------------
When given any epoc style date this function sets the time of that epoc to midnight precicely.

	$this->EpocTools = new EpocTools();
	$this->EpocTools->date(time()); // Returns the epoc at midnight today
	$this->EpocTools->date(strtotime('2011-01-01 04:32:21')); // Returns midnight on 01/01/2011


`epocdateend` - Return the last second of a given epoc date
-----------------------------------------------------------
In contrast to `epocdate` this function returns the last possible second of a given date.

	$this->EpocTools = new EpocTools();
	$this->EpocTools->dateend(time()); // Returns the last possible second of todays date
	$this->EpocTools->dateend(strtotime('2011-01-01 04:32:21')); // Returns 23:59:59 on 01/01/2011


`getstamp` - The inverse of `date()`
------------------------------------
This function takes a given string and converts it into a date using the template provided.
This is in contrast to the default PHP Date() function which does the opposit.


	$this->EpocTools = new EpocTools();
	$this->EpocTools->getstamp('d/m/Y H:M','02/07/1983 1035'); // Returns unix timestamp 425952900
	

`humanize` - Returns a sequence of seconds in human format
----------------------------------------------------------
This function can be used to return how far an event occurs in the past or future relative to the current time

	$this->EpocTools = new EpocTools();
	$this->EpocTools->Humanize(time() - 60); // Returns '60 seconds ago'
	$this->EpocTools->Humanize(time() - 5400); // Returns '1 hour, 30 minutes ago'
	$this->EpocTools->Humanize(time() - 72576000); // Returns '2 weeks ago'

A vareity of options can be specified including how many time scales should be selected (i.e. how specific) for example '2 weeks, 1 hour' would have two blocks, '2 weeks, 1 hour, 38 minutes, 42 seconds' would have four.


`shorthand` - Return the number of seconds from a human readable time shorthand
-------------------------------------------------------------------------------
This function returns the number of seconds determined from a shorthand time string.

	$this->EpocTools = new EpocTools();
	$this->EpocTools->Shorthand('1h') // Returns 3600 (1 hour - 60*60)
	$this->EpocTools->Shorthand('1h30m') // Returns 5400 (1 hour 30 minutes - 60*60 + 30*60)
	$this->EpocTools->Shorthand('2w') // Returns 72576000 (2 weeks - 7*24*60*60*60*2)
