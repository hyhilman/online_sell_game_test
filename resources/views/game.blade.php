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

    $('form.buy').html('<input type="text" name="game_id" value='+result.id+'>')
    @if (Auth::check())
    $('form.buy').append('<input type="text" name="user_id" value="{{ Auth::user()->id }}">')
    @endif
    $('form.buy').append('<input type="hidden" name="_token" value="{{ csrf_token() }}">')

    $(".btn.buy").on('click', function(event){
        event.preventDefault();
        $("form.buy").submit();
    });

    $('form.buy').on('submit', function(event) {
        event.preventDefault();
        @if (Auth::check())
        $.ajax({
            url : $(this).attr('action'),
            type: "post",
            headers: {
                "authorization": "Bearer {{ Auth::user()->api_token }}",
                "content-type": "application/x-www-form-urlencoded",
                "cache-control": "no-cache"
            },
            data: $(this).serialize(),
            dataType: 'JSON',
            success: function (data) {
                $('.balance').html(data.userbalance.balance);
                Swal.fire({
                  type: 'success',
                  title: 'Success buy',
                  showConfirmButton: false,
                  timer: 1500
                })
            },
            error: function (jXHR, textStatus, errorThrown) {
                Swal.fire({
                  title: 'Error!',
                  text: jXHR.responseJSON.message,
                  type: 'error',
                  confirmButtonText: 'Ok'
                })
            }
         });
         @else
         $(location).attr('href', "{{ url('login')}}")
         @endif
    });
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
                  <form class="buy" action="{{ URL::to('api/order') }}" method="POST" style="display: none;"></form>
              </div>
          </div>
      </div>
    </div>
</div>
@endsection
