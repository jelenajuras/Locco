@extends('layouts.admin')

@section('title', 'Nova poruka')

@section('content')
<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
	<i class="fas fa-angle-double-left"></i>
	Natrag
</a>
<div class="shedulePost row">
    <div class="col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Zahtjev za rasporedom</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" name="myForm" role="form" method="post" action="{{ route('admin.posts.store') }}" onsubmit="return validateForm()">
                <fieldset>
					<input name="tip" type="hidden" value="raspored" />
					<input name="to_employee_id" type="hidden" value="uprava" />
					<input name="title" type="hidden" value="Raspored" />
					<input type="text" name="content" value="Ja, {{ $user->first_name . ' ' .  $user->last_name}}, molim da mi se dodijeli raspored za" class="form-control" readonly />
					<input name="user_id" type="hidden" value="{{ $user->id }}" />
					<div class="form-group">
						<input name="datum" type="date" class="datum form-control" />
					</div>
					<div class="form-group">
						<span>od</span>
						<input name="vrijemeOd" type="time" class="datum" value="08:00" />
						<span>do</span>
						<input name="vrijemeDo" type="time" class="datum " value="16:00" />
					</div>
                    {{ csrf_field() }}
                    <input class="btn btn-lg btn-primary btn-block" type="submit" value="Pošalji" id="stil1">
                </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- validator  -->
<script>
	function validateForm() {
		var x = document.forms["myForm"]["datum"].value;
		if (x === "") {
			alert("Nemoguće poslati zahtjev. Nije upisan datum");
			return false;
		}
	}
</script>
@stop
