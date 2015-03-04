@extends('errors.layout_auth')

@section('content')
  <i class="icon-404"></i>
  <h1>404</h1>
  <h2>page not found</h2>
  <p class="page-404">Something went wrong or that page doesn’t exist yet. <a href="{{ URL::previous() }}">Back</a></p>
 @stop