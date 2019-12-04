@extends('layouts.admin')

@section('title', 'Kategorije opreme')

@section('content')
<div class="">
     <div class="page-header">
        <a class="btn btn-md pull-left" href="{{ url()->previous() }}">
            <i class="fas fa-angle-double-left"></i>
            Natrag
        </a>
        <h2>Kategorije opreme</h2>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @if(count($catalog_categories) > 0)
                @foreach ($catalog_categories as $catalog_category) 
                    <div class="catalog_category" >
                        <h4 class="category_name">{!! $catalog_category->name !!}</h4>
                        <p>{!! $catalog_category->description !!}</p>
                        <div class="catalog_marufacturer" >
                            @foreach ( $catalog_manufacturers->where('category_id', $catalog_category->id) as $manufacturer)
                                <div class="manufacturer" >
                                    <h5><a href="{{ $manufacturer->url }}" target="_blanck" >{{ $manufacturer->name }}</a></h5>
                                    @if($manufacturer->phone)<p>Telefon: {{ $manufacturer->phone }}</p>@endif
                                    @if($manufacturer->email)<p>Email: {{ $manufacturer->email }}</p>@endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
			@else
				{{'Nema unesenih podataka!'}}
            @endif
            </div>
        </div>
    </div>
</div>
<script>
    $('.category_name').click(function(){
        $(this).siblings('.catalog_marufacturer').toggle();
        $(this).parent().siblings().toggle();
    });
</script>
@stop
