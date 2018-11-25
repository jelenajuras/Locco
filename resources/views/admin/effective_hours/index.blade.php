@extends('layouts.admin')

@section('title', 'ECH')
<link rel="stylesheet" href="{{ URL::asset('css/ech.css') }}" type="text/css" >
@section('content')
<div class="">
    <div class="page-header">
        <!--<div class='btn-toolbar pull-right' >
            <a class="btn btn-primary btn-lg" href="{{ route('admin.effective_hours.create') }}"  id="stil1" >
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                Novo vozilo
            </a>
        </div>-->
        <h2>Efektivna cijena sata</h2>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
			@if(count($registrations) > 0)
                <table id="table_id" class="display" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Djelatnik</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
					@foreach ($registrations as $registration)
						@if(!DB::table('employee_terminations')->where('employee_id',$registration->employee_id)->first() )
							<?php
								$effectiveHour = DB::table('effective_hours')->where('employee_id',$registration->employee_id)->first();
								$ech = '';
								$brutto = '';
							?>
							<tr>
								<td class="ech">
									@if($effectiveHour)
										<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.effective_hours.update', $effectiveHour->id) }}">
										{{ csrf_field() }}
										{{ method_field('PUT') }}
									@else
										<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.effective_hours.store') }}">
									@endif
										<input name="employee_id" type="hidden" value="{{ $registration->employee_id}}" /><span>{{ $registration->first_name . ' ' . $registration->last_name }}</span>
										@foreach($effectiveHours as $effectiveHour)
											<?php 
												if($effectiveHour->employee_id == $registration->employee_id){
													$ech = str_replace('.',',', $effectiveHour->effective_cost);
													$brutto = str_replace('.',',', $effectiveHour->brutto);
												} 
											?>
											
										@endforeach
											<input class="{{ ($errors->has('effective_cost')) ? 'has-error' : '' }}" placeholder="ECH" name="effective_cost" type="text" value="{{ $ech }}" title="Unesi iznos efektivne cijene sata"/>
											<input class="{{ ($errors->has('brutto')) ? 'has-error' : '' }}" placeholder="brutto" name="brutto" type="text" value="{{  $brutto }}"  title="Unesi iznos brutto godišnje plaće"/>
											<input name="_token" value="{{ csrf_token() }}" type="hidden">
										
										<input type="submit" value="&#10004" title="click to save datas">
										{!! ($errors->has('effective_cost') ? $errors->first('effective_cost', '<p class="text-danger">:message</p>') : '') !!}
										{!! ($errors->has('brutto') ? $errors->first('brutto', '<p class="text-danger">:message</p>') : '') !!}
									</form>
								</td>
							</tr>
							
                        
						@endif
                    @endforeach
					</tbody>
                </table>
			@else
				{{'Nema zapisa!'}}
			@endif
            </div>
        </div>
    </div>
</div>

@stop
