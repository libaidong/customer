<? $this->site->view('lib/fullcalendar') ?>
<script src="/js/calendar.js"></script>
<? $this->site->view('calendar/event') ?>
<div class="dcu-agenda" id="calendar"></div>
<script>
$(function() {
	loadCalendar('#calendar');
});
</script>
