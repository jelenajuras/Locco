@extends('layouts.admin')

@section('title', 'Edukacija')
<link rel="stylesheet" href="{{ URL::asset('css/education.css') }}"/>
@section('content')
<div class="post" >
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-lg-offset-1">
		<a class="btn btn-md pull-left" href="{{ url()->previous() }}">
			<i class="fas fa-angle-double-left"></i>
			Natrag
		</a>
		<h3>{{ $education->name }}</h3>
		<input type="text" id="myInput" onkeyup="mySearch()" placeholder="Search..." title="Type in...">
		<div id="articles">
			@foreach($educationArticles->where('status','aktivan') as $educationArticle)
				<div class="article panel panel-default">
					<div class="panel-heading">
						<h4>{{ $educationArticle->educationTheme['name'] }}</h4>
					</div>
					<div class="panel-body">
						<small><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{ $educationArticle->employee['first_name'] . ' ' . $educationArticle->employee['last_name'] }} | <span class="glyphicon glyphicon-time" aria-hidden="true"></span> {{ \Carbon\Carbon::createFromTimeStamp(strtotime($educationArticle->created_at))->diffForHumans() }} </small>
						<h4>{{ $educationArticle->subject }}</h4>
						<div class="panel-body">
							{!! $educationArticle->article !!}
						</div>	
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-lg-offset-1">
							<h5>Komentiraj!</h5>
							<form accept-charset="UTF-8" role="form" method="post" action="{{ route('admin.education_comments.store', $educationArticle->id) }}">
								<div class="form-group {{ ($errors->has('comment')) ? 'has-error' : '' }}">
								   <textarea class="form-control" name="comment" id="post-content" rows="5"  ></textarea>
									{!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
								</div>
							
								<input type="hidden" name="article_id" value="{{$educationArticle->id }}">
								<input type="hidden" name="education_id" value="{{$education->id }}">
								{{ csrf_field() }}
								<input class="btn btn-lg btn-primary" type="submit" value="Spremi komentar" id="stil1">
							</form>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-lg-offset-1">
							<h4 id="Comments">Komentari</h4>
							@if(count($educationComments) > 0)
								@foreach($educationComments->where('article_id',$educationArticle->id) as $comment)
									<div class="media">
										<div class="media-left">
											<a href="#">
												<img class="media-object" src="//www.gravatar.com/avatar/{{ md5($educationArticle->employee['email']) }}?d=mm">
												</a>
										</div>
										<div class="media-body">
											<h5 class="media-heading">{{ $educationArticle->employee['email'] }} | <small>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($educationArticle->created_at))->diffForHumans() }} </small></h5>
											{{ $comment->comment}}
										</div>
									</div>
									<hr>
								@endforeach	
							
							@else
								<p>{{'No Comments!'}}</p>	
							@endif
						</div>
					</div>
				</div>
			@endforeach
		</div>
	</div>
</div>
<script>
function mySearch() {
    var input, filter, element1, article, a, b, i, p, p1, txtValue, txtValue1, txtValue2, txtValue3;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    element1 = document.getElementById("articles");
    article = element1.getElementsByClassName("article");
    for (i = 0; i < article.length; i++) {
        a = article[i].getElementsByTagName("h4")[0];
        b = article[i].getElementsByTagName("h4")[1];
        p = article[i].getElementsByTagName("p")[0];
        p1 = article[i].getElementsByTagName("p")[1];
		
		if(a != 'undefined') {
			txtValue = a.textContent || a.innerText;
		}
        if(b != 'undefined') {
			txtValue1 = b.textContent || b.innerText;
		}
		if(p != 'undefined') {
			txtValue2 = p.textContent || p.innerText;
		}

        if (txtValue.toUpperCase().indexOf(filter) > -1 || txtValue1.toUpperCase().indexOf(filter) > -1 || txtValue2.toUpperCase().indexOf(filter) > -1 ) {
            article[i].style.display = "";
        } else {
            article[i].style.display = "none";
        }
    }
}
</script>
@stop
