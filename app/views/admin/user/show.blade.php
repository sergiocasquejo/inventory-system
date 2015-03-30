@extends('admin.layout_master')

@section('content')
    @include ('admin._partials.breadcrumbs')
    <div class="row">
       @include ('admin._partials.profile', ['user' => $user])
      <aside class="profile-info col-lg-9">
          <section class="panel">
              <div class="bio-graph-heading">
                  <h1> Profile Info</h1>
              </div>
              <div class="panel-body bio-graph-info">
                  <div class="row">
                      <div class="bio-row">
                          <p><span>First Name </span>: {{ $user->first_name }}</p>
                      </div>
                      <div class="bio-row">
                          <p><span>Last Name </span>: {{ $user->last_name }}</p>
                      </div>
                      <div class="bio-row">
                          <p><span>Birthday</span>: {{ date('F j, Y', strtotime($user->birthdate)) }}</p>
                      </div>
                      <div class="bio-row">
                          <p><span>Email </span>: {{ $user->email }}</p>
                      </div>
                      <div class="bio-row">
                          <p><span>Contact No. </span>: {{ $user->contact_no }}</p>
                      </div>
                      <div class="bio-row">
                          <p><span>Address </span>: {{ $user->address }}</p>
                      </div>
                  </div>
              </div>
          </section>
          
      </aside>
  </div>
@stop