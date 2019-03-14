@extends('layouts.app')

@section('js')
<script>
$.ajax({
    "url": "{{URL::to('api/games/'.$id)}}",
    "method": "GET",
    "headers": {
        "content-type": "application/json",
        "cache-control": "no-cache"
    }
}).done(function (result) {
    publisher = $('.publisher').clone().html(result.publisher);
    $('.title').html(result.title+" ");
    $('.title').append(publisher.clone());
    $('.price').html(result.price+" $");
    $('.date').append(result.release_date);
    $('.sell').append(result.order_count);
    $('.description').append(result.description);
    $('.img-thumbnail').attr('src',result.image_url);

})
</script>
@stop

@section('content')
<div class="container">

    <div class="jumbotron">
      <div class="container">
          <div class="row">
              <div class="col col-sm-5">
                  <img src="" class="img-thumbnail"/>
              </div>
              <div class="col col-sm-7">
                  <p class="h1 title">title <span class="publisher muted h3"> publisher</span ></p>
                  <p class="lead font-weight-bold price"></p>
                  <p class="muted date">Release Date : </p>
                  <p class="muted sell">Total Sell : </p>
                  <p class="lead description">Description : <br></p>
                  <a href="#" class="btn btn-primary mx-auto buy">Buy Now!</a>
              </div>
          </div>
      </div>
    </div>
</div>
@endsection
