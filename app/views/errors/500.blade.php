@extends('errors.layout_auth')

@section('content')
  <i class="icon-500"></i>
  <h1>Ouch!</h1>
  <h2>500 Page Error</h2>
  <p class="page-500">Looks like Something went wrong. <a href="{{ URL::previous() }}">Back</a></p>
@stop