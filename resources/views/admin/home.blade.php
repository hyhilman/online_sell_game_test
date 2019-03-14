@extends('layouts.app')

@section('js')
<script> // this is script for showing create content

</script>
@stop

@section('content')
<div class="container">

    <div class="row justify-content-center">
      <div class="col-3">
        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
          <a class="nav-link active" id="v-pills-create-tab" data-toggle="pill" href="#v-pills-create" role="tab" aria-controls="v-pills-create" aria-selected="true">Insert New Game</a>
          <a class="nav-link" id="v-pills-update-tab" data-toggle="pill" href="#v-pills-update" role="tab" aria-controls="v-pills-update" aria-selected="false">Update Game</a>
          <a class="nav-link" id="v-pills-delete-tab" data-toggle="pill" href="#v-pills-delete" role="tab" aria-controls="v-pills-delete" aria-selected="false">Delete Game</a>
        </div>
      </div>
      <div class="col-9">
        <div class="tab-content" id="v-pills-tabContent">
          <div class="tab-pane fade show active" id="v-pills-create" role="tabpanel" aria-labelledby="v-pills-create-tab">@include('admin.games.create')</div>
          <div class="tab-pane fade" id="v-pills-update" role="tabpanel" aria-labelledby="v-pills-update-tab">...</div>
          <div class="tab-pane fade" id="v-pills-delete" role="tabpanel" aria-labelledby="v-pills-delete-tab">...</div>
        </div>
      </div>
    </div>
</div>
@endsection
