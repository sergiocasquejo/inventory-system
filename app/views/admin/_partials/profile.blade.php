<aside class="profile-nav col-lg-3">
    <section class="panel">
        <div class="user-heading round">
            <a href="#">
                <img src="{{ $user->avatar()->medium }}" alt="">
            </a>
            <h1>{{{ $user->first_name.' '.$user->last_name }}}</h1>
            <p>{{{ $user->email }}}</p>
        </div>

        <ul class="nav nav-pills nav-stacked">
            <li class="active"><a href="{{ route('admin_users.show', $user->id) }}"> <i class="icon-user"></i> Profile</a></li>
            @if ($user->isAdmin())
            <li><a href="{{ route('admin_users.edit', $user->id) }}"> <i class="icon-edit"></i> Edit profile</a></li>
            @endif
        </ul>

    </section>
</aside>