@extends('layouts.admin')

@section('title', 'Kalendar')

@section('css')
	<link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">
@stop

@section('js')
	<script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
@stop

@section('content')

<div id="gantt_here"></div>
<script type="text/javascript">
    gantt.config.columns=[
		{name:"text",       label:"Naziv zadatka",  tree:true, width:'*' },
		{name:"start_date", label:"Početak", align: "center" },
		{name:"duration",   label:"Trajanje",   align: "center" },
		{name:"add",        label:"" }
	];
	
	gantt.config.lightbox.sections = [
		{name:"description", height:38, map_to:"text", type:"textarea", focus:true},
		{name:"priority", height:22, map_to:"priority", type:"select", options: [ 
			{key:1, label: "High"},                                               
			{key:2, label: "Normal"},                                             
			{key:3, label: "Low"}                                                 
		]},                                                                      
		{name:"time", height:72, type:"duration", map_to:"auto"}
	];
 
	gantt.locale.labels.section_priority = "Priority";
	
	gantt.config.time_picker = "%H:%s";
	gantt.config.time_step = 15;
	gantt.config.xml_date = "%Y-%m-%d %H:%i:%s";
	gantt.attachEvent("onBeforeGanttRender", function(){
	var range = gantt.getSubtaskDates();
	var scaleUnit = gantt.getState().scale_unit;
	if(range.start_date && range.end_date){
	 gantt.config.start_date = gantt.calculateEndDate(range.start_date, -4, scaleUnit);
	 gantt.config.end_date = gantt.calculateEndDate(range.end_date, 5, scaleUnit);
	}
	});
	var daysStyle = function(date){
		var dateToStr = gantt.date.date_to_str("%D");
		if (dateToStr(date) == "Sun"||dateToStr(date) == "Sat")  return "weekend";
	 
		return "";
	};
	gantt.config.scale_unit = "month";
	gantt.config.date_scale = "%m - %Y";
 
	gantt.config.subscales = [
		{unit:"week", step:1, date:"%W week"},
		{unit:"day", step:1, date:"%d (%D)",css:daysStyle }
	];
	gantt.config.scale_height = 70;
	
	gantt.config.order_branch = true;
	gantt.config.order_branch_free = true;
	gantt.config.autosize = true;
 
	gantt.init("gantt_here");
	
	gantt.load("/api/data");
	var dp = new gantt.dataProcessor("/api");
	dp.init(gantt);
	dp.setTransactionMode("REST");
	
</script>

@stop