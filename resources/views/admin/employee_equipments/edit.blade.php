@extends('layouts.admin')

@section('title', 'Povrat zadužene opreme')

@section('content')
<div class="page-header">
  <h2>Povrat zadužene opreme</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.employee_equipments.update', $employeeEquipments->id ) }}">
					 
						<div class="form-group">
							<span><b>Ime i prezime:</b></span>
							<span>{{ $employeeEquipments->employee['first_name'] . ' ' }}</span>
							<input type="hidden" name="employee_id" type="text" class="form-control" value="{{ $employeeEquipments->employee_id }}">
							{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
							</br>
						</div>
						<div class="form-group">
						<label>Datum povrata: </label>
						<input name="datum_povrata" class="date form-control" type="text" value = "{{ Carbon\Carbon::now()->format('d-m-Y') }}">
						</div>
						<script type="text/javascript">
									$('.date').datepicker({  
									   format: 'dd-mm-yyyy'
									 });  
						</script>
						<div class="row">
						<div class="form-group">
							<div class="col-md-6 col-md-offset-1" >
								<table>
								
								<?php
								$oprema=DB::table('employee_equipments')->where('employee_id', $employeeEquipments->employee_id)->get();
								$naziv=DB::table('equipment')->where('id', $employeeEquipments->equipment_id)->value('naziv');
								?>
								<tr>
									<td width="250">
										<div class="checkbox" name="equipment_id1[]">
											<label>
												<input type="checkbox" name="equipment_id1[]" value="{{ $employeeEquipments->equipment_id }}" checked>
													{{ $naziv . '(' . $employeeEquipments->kolicina . ')' }}
											</label>
										</div>
									</td>
								</tr>
								
								
								</table>
							</div>
						</div>
						</div>

							<div class="form-group">
								<label>Napomena</label>
								<textarea class="form-control" name="napomena">{{ $employeeEquipments->napomena }}</textarea>
							</div>
						{{ csrf_field() }}
						{{ method_field('PUT') }}
						<input name="_token" value="{{ csrf_token() }}" type="hidden">
						<input class="btn btn-lg btn-primary btn-block" type="submit" value="Vrati opremu" id="stil1">
					
				</form>
			</div>
		</div>
	</div>
</div>

@stop