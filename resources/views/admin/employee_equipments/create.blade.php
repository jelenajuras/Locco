@extends('layouts.admin')

@section('title', 'Zaduženje opreme')

@section('content')

<?php 
$employee = $employees->where('id', $employee)->first();
?>
<div class="page-header">
  <h2>Zaduženje radne opreme</h2>
</div> 
<div class="">
	<div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
		<div class="panel panel-default">
			<div class="panel-body">
				<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.employee_equipments.store') }}">
					 
						<div class="form-group">
							<span><b>Ime i prezime:</b></span>
							<span>{{ $employee->first_name . ' ' . $employee->last_name }}</span>
							<input type="hidden" name="employee_id" type="text" class="form-control" value="{{ $employee->id }}">
							{!! ($errors->has('employee_id') ? $errors->first('employee_id', '<p class="text-danger">:message</p>') : '') !!}
							</br>
							
						</div>
						
						<div class="form-group">
						<label>Datum zaduženja: </label>
						<input name="datum_zaduzenja" class="date form-control" type="text" value = "{{ Carbon\Carbon::now()->format('d-m-Y') }}">
						</div>
						<script type="text/javascript">
									$('.date').datepicker({  
									   format: 'dd-mm-yyyy'
									 });  
						</script>
						<div class="row">
						<div class="form-group">
							<div class="col-md-4 col-md-offset-1">
								<table>
								<label>Monter</label>
								@foreach(DB::table('equipment')->where('količina_monter','>',0)->orderBy('naziv','ASC')->get() as $oprema)
								
								<tr>
									<td>
										<div class="checkbox" name="equipment_id1[]">
											<label>
												<input type="checkbox" name="equipment_id1[]" value="{{ $oprema->id }}">
													<?php ?>
													{{ $oprema->naziv . '(' . $oprema->količina_monter . ')'}}
												<!--<input name="kolicina[]" class="form-control" value="{{ $oprema->količina_monter }}" type="hidden">-->
											</label>
										</div>
									</td>
								
								@endforeach
								 </tr>
								</table>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-4 col-md-offset-2">
								<table>
								<label>Inženjer</label>
								@foreach(DB::table('equipment')->where('količina_inženjer','>',0)->orderBy('naziv','ASC')->get() as $oprema)
								
								
								<tr>
									<td>
										<div class="checkbox" name="equipment_id2[]">
											<label>
												<input type="checkbox" name="equipment_id2[]"  value="{{ $oprema->id }}">
													<?php ?>
													{{ $oprema->naziv . '(' . $oprema->količina_inženjer . ')'}}
												
											</label>
										</div>
									</td>
								</tr>
							
								@endforeach
								 
								</table>
							</div>
						</div>
						</div>
						
							<div class="form-group">
								<label>Napomena</label>
								<textarea class="form-control" name="napomena"></textarea>
							</div>

						<input name="_token" value="{{ csrf_token() }}" type="hidden">
						<input class="btn btn-lg btn-primary btn-block" type="submit" value="Zaduži opremu" id="stil1">
					
				</form>
			</div>
		</div>
	</div>
</div>

@stop