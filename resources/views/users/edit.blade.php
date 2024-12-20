<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dynamic Dependent Dropdown</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta name="_token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="bg p-3 text-white shadow-lg text-center">
        <h4>Laravel Dynamic Dependent Dropdown | Country State City | Jquery AJAX</h4>
    </div>
    <div class="container mt-3">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                <a href="{{ url('/list') }}" class="btn btn-primary mb-3">Back</a>
                <div class="card card-primary p-4 border-0 shadow-lg">
                    <form action="" id="frm" name="frm" method="post">
                        <div class="card-body">
                            <h3>Edit User</h3>
                            <div class="mb-3">
                                <input type="text" class="form-control-lg form-control" name="name" id="name"
                                    placeholder="Enter Name" value="{{ $user->name }}">
                                <p class="invalid-feedback" id="name-error"></p>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control-lg form-control" name="email" id="email"
                                    placeholder="Enter Email" value="{{ $user->email }}">
                                <p class="invalid-feedback" id="email-error"></p>
                            </div>
                            <div class="mb-3">
                                <select name="country" id="country" class="form-control-lg form-select">
                                    <option value="">Select Country</option>
                                    @if (!empty($countries))
                                        @foreach ($countries as $country)
                                            <option {{ $user->country == $country->id ? 'selected' : '' }}
                                                value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="mb-3">
                                <select name="state" id="state" class="form-control-lg form-select">
                                    <option value="">Select State</option>
                                    @if (!empty($states))
                                        @foreach ($states as $state)
                                            <option {{ $user->state == $state->id ? 'selected' : '' }}
                                                value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="mb-3">
                                <select name="city" id="city" class="form-control-lg form-select">
                                    <option value="">Select City</option>
                                    @if (!empty($cities))
                                        @foreach ($cities as $city)
                                            <option {{ $user->city == $city->id ? 'selected' : '' }}
                                                value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-primary btn-lg">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!--https://blog.jquery.com/2023/08/28/jquery-3-7-1-released-reliable-table-row-dimensions/ https://getbootstrap.com/ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $("#country").change(function() {
                var country_id = $(this).val();

                if (country_id == "") {
                    var country_id = 0;
                }

                $.ajax({
                    url: '{{ url('/fetch-states/') }}/' + country_id,
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        $('#state').find('option:not(:first)').remove();
                        $('#city').find('option:not(:first)').remove();

                        if (response['states'].length > 0) {
                            $.each(response['states'], function(key, value) {
                                $("#state").append("<option value='" + value['id'] +
                                    "'>" + value['name'] + "</option>")
                            });
                        }
                    }
                });
            });


            $("#state").change(function() {
                var state_id = $(this).val();

                console.log(state_id);

                if (state_id == "") {
                    var state_id = 0;
                }

                $.ajax({
                    url: '{{ url('/fetch-cities/') }}/' + state_id,
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        $('#city').find('option:not(:first)').remove();

                        if (response['cities'].length > 0) {
                            $.each(response['cities'], function(key, value) {
                                $("#city").append("<option value='" + value['id'] +
                                    "'>" + value['name'] + "</option>")
                            });
                        }
                    }
                });
            });

        });

        $("#frm").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: '{{ url('/edit/' . $user->id) }}',
                type: 'post',
                data: $("#frm").serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response['status'] == 1) {
                        window.location.href = "{{ url('list') }}";
                    } else {
                        if (response['errors']['name']) {
                            $("#name").addClass('is-invalid');
                            $("#name-error").html(response['errors']['name']);
                        } else {
                            $("#name").removeClass('is-invalid');
                            $("#name-error").html("");
                        }

                        if (response['errors']['email']) {
                            $("#email").addClass('is-invalid');
                            $("#email-error").html(response['errors']['email']);
                        } else {
                            $("#email").removeClass('is-invalid');
                            $("#email-error").html("");
                        }
                    }
                }
            });
        })
    </script>
</body>

</html>
