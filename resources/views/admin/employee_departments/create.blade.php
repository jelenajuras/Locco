@extends('layouts.admin')

@section('title', 'Zaposlenici po odjelima')
<?php 
	use App\Models\Employee_department; 
?>
@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="">
    <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.employee_departments.store') }}">
        <div class="page-header">
            <h1>Pridruži djelatnika {{ $employee->first_name . ' ' . $employee->last_name }} odjelu</h1>
            <input class="btn btn-submit" type="submit" value="Spremi podatke" title="Snimi promjenu">
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <input type="hidden" name="employee_id" value="{{ $employee->id }}" />
                @foreach ($departments as $department)
                    <span style="padding: 0 10px; float:left;width:25%;"><input type="checkbox" name="department_id[]" value="{{ $department->id }}" 
                    {!!  $employee_departments->where('department_id',$department->id)->first() ? 'checked' : '' !!}/>{{ $department->name }}</span>
                @endforeach	
                {{ csrf_field() }}
            </div>
        </div>
    </form>
</div>
@stop