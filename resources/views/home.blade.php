@extends('layouts.app')

@section('js')
<script> // this is script for showing home content
    // clonning required html
    var game = $("#game-card").clone().removeAttr('id').removeAttr('hidden');
    var page = $("#paggination-item").clone().removeAttr('id').removeAttr('hidden');

    load("{{ url('api/games'.'?page='.app('request')->input('page')) }}")
    // function for loading home preview
    function load(url){
        // clear the game list content
        $('#game-list').empty();

        $.ajax({
            "url": url,
            "method": "GET",
            "headers": {
                "content-type": "application/json",
                "cache-control": "no-cache"
            }
        }).done(function (result) {
            // game list preview
            for (let i = 0; i < result.data.length; i++) {
                game.find('img').attr('src', result.data[i].image_url);


                game.find('.card-title').html(result.data[i].title);
                game.find('.publisher').html("Publisher : " + result.data[i].publisher);

                game.find('.card .btn.buy').html("Buy $" + result.data[i].price);
                game.find('.card .btn.buy').attr('data-gameid', result.data[i].id);

                game.find('.card .btn').attr('href', "{{ URL::to('game/') }}/"+result.data[i].id);

                game.find('form').attr('id','BuyGame'+result.data[i].id);
                game.find('form').addClass('buy');
                game.find('form').html('<input type="text" name="game_id" value='+result.data[i].id+'>')
                @if (Auth::check())
                game.find('form').append('<input type="text" name="user_id" value="{{ Auth::user()->id }}">')
                @endif
                game.find('form').append('<input type="hidden" name="_token" value="{{ csrf_token() }}">')
                game.find('form').append('<input type="submit">');

                $('#game-list').append(game.clone());
            }

            $(".card .btn.buy").on('click', function(event){
                event.preventDefault();
                $("#BuyGame"+$(this).data('gameid')).submit();
            });

            $('#game-list .buy').on('submit', function(event) {
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

            for (let i = 1; i <= result.last_page; i++) {
                page.find('a').attr('href', "{{ url('home') }}?page="+i).html(i);
                if(i == result.current_page) {
                    page.addClass('active');
                } else {
                    page.removeClass('active');
                }
                $('#pagination').append(page.clone());
            }

        })
    }
</script>
@stop

@section('content')
<div class="container">

    <nav aria-label="Page navigation">
      <ul class="pagination justify-content-center" id="pagination">
        <li id="paggination-item" class="page-item" hidden><a class="page-link" href="#">Previous</a></li>
      </ul>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row justify-content-center" id="game-list">
                <div id="game-card" class="col-md-3 p-2" hidden>
                    <div class="card">
                        <img src="" height="180px"/>
                        <div class="card-body">
                          <h5 class="card-title">Card title</h5>
                          <h6 class="publisher card-subtitle mb-2 text-muted">Card subtitle</h6>
                          <a href="#" class="btn btn-primary mx-auto buy">Go somewhere</a>
                          <a href="#" class="btn btn-primary mx-auto">Detail</a>
                          <form action="{{ URL::to('api/order') }}" method="POST" style="display: none;"></form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
