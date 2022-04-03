@if ($errors->any())

    <div class="alert alert-danger">

        <ul>

            @foreach ($errors->all() as $error)

                <li style="font-weight: bold; font-size: 12px;">{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


@if(session('success'))

    <div class="alert alert-success lara-error">

        <span class="fa fa-exclamation-circle"></span>  <span style="font-weight: bold; font-size: 12px;">{{session('success')}}</span>

    </div>

@endif

@if(session('info'))

    <div class="alert alert-info lara-error">

        <span class="fa fa-exclamation-circle"></span> <span style="font-weight: bold; font-size: 12px;">{{session('info')}}</span>

    </div>

@endif

@if(session('error'))

    <div class="alert alert-danger lara-error">

        <span class="fa fa-exclamation-circle"></span> <span style="font-weight: bold; font-size: 12px;">{{session('error')}}</span>

    </div>

@endif
