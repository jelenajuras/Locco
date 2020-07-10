@if (! Sentinel::inRole('erp_test'))
	
@extends('layouts.admin')

@section('title', 'ECH')
<link rel="stylesheet" href="{{ URL::asset('css/ech.css') }}" type="text/css" >
@section('content')
<div class="">
    <div class="page-header">
        <h2>Efektivna cijena sata</h2>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			@if(count($registrations) > 0)
				<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.effective_hours.store') }}" >
					@foreach ($registrations as $registration)
						@if(!DB::table('employee_terminations')->where('employee_id',$registration->employee_id)->first() )
							<?php
								$effectiveHour = $effectiveHours->where('employee_id',$registration->employee_id)->first();
								$ech = '';
								$brutto = '';
							?>
							<div class="ech">
								<input name="employee_id[{{ $registration->employee_id}}]" type="hidden" value="{{ $registration->employee_id}}" /><span>{{ $registration->first_name . ' ' . $registration->last_name }}</span>
								@php
									if($effectiveHour) {
										$ech = str_replace('.',',', $effectiveHour->effective_cost);
										$brutto = str_replace('.',',', $effectiveHour->brutto);
									}
								@endphp
								<input class="{{ ($errors->has('effective_cost')) ? 'has-error' : '' }}" placeholder="ECH" name="effective_cost[{{ $registration->employee_id}}]" type="text" value="{{ $ech }}" title="Unesi iznos efektivne cijene sata" />
								<input class="{{ ($errors->has('brutto')) ? 'has-error' : '' }}" placeholder="brutto" name="brutto[{{ $registration->employee_id}}]" type="text" value="{{  $brutto }}"  title="Unesi iznos brutto godišnje plaće"/>
								{!! ($errors->has('effective_cost') ? $errors->first('effective_cost', '<p class="text-danger">:message</p>') : '') !!}
								{!! ($errors->has('brutto') ? $errors->first('brutto', '<p class="text-danger">:message</p>') : '') !!}
							</div>
						@endif
					@endforeach
					{{ csrf_field() }}
					<input class="btn-submit" type="submit" value="Upiši" style=" margin: 20px; padding: 10px 20px; border-radius: 5px; background: lightgrey; color: black; align-items: right; float: right;" >
				</form>
			@endif
        </div>
    </div>
</div>
@stop
@endif