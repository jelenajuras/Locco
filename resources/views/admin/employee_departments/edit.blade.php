@extends('layouts.admin')

@section('title', 'Zaposlenici po odjelima')
<?php 
	use App\Models\Employee_department; 
	$i= 0;
?>
@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="">
    <form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.employee_departments.update', $department->id) }}">
        <div class="page-header">
            <h1>Pridruži djelatnike odjelu {{ $department->name }}</h1>
            <input class="btn btn-submit" type="submit" value="Spremi podatke" title="Snimi promjenu">
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <input type="hidden" name="department_id" value="{{ $department->id }}" />
                @foreach ($registrations as $registration)
                    <span style="padding: 0 10px; float:left;width:25%;"><input type="checkbox" name="employee_id[]" value="{{ $registration->employee_id }}" 
                    {!!  Employee_department::where('employee_id',$registration->employee_id)->where('department_id',$department->id)->first() ? 'checked' : '' !!}/>{{ $registration->employee['first_name'] . ' ' . $registration->employee['last_name'] }}</span>
                @endforeach	
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                   
            </div>
        </div>
    </form>
    <label class="label_check" for="checkAll">Označi sve</label><input id="checkAll" type="checkbox"  />
</div>

<script>
    $('#checkAll').click(function () { 
        if ($('input:checkbox').prop( "checked" )) {
            $('input:checkbox').prop('checked',false);
        } else {
            $('input:checkbox').prop('checked',true);
        }    

    });
</script>
@stop