@include('layouts.app')

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <b>Profile Image</b><br>
            @if($user_image[0] !='')
            <img src="/images/user_images/{{$user_image[0]}}" class="img-fluid profile_image mt-4" />
            @else
            <img src="/images/user_images/IFAVE_PNG.png" class="img-fluid profile_image mt-4" />
            @endif
        </div>
        <div class="col-md-8">
            <form action="{{url('/update-user-profile')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @foreach($user_info as $info)
                <div class="mb-3">
                    <label for="name">Nickname</label>
                    <input type="text" class="form-control" id="name" value="{{$info->name}}" readonly>
                </div>
                <div class="mb-3">
                    <label for="formFile" class="form-label">Profile Picture<b class="text-danger"> ( 200 x 200 )</b></label>
                    <input class="form-control" type="file" id="formFile" name="profile_picture">
                </div>
                <div class="mb-3">
                    <label for="bio">Bio</label>
                    <textarea name="bio" id="bio" cols="114" rows="10" value="{{$info->bio}}" required> {{$info->bio}}</textarea>
                </div>
                @endforeach
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@include('footer.footer')