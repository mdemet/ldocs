
@extends('layouts.app')

@section('content')

    <div class="container">
        @foreach($class_types as $class_type)
            <h1> {{ $class_type->name }} </h1>
            @foreach ($class_type->namespaces as $namespace)
                <h2> {{ $namespace->name }} </h2>
                @foreach ($namespace->classes as $class)
                    <h3> {{ $class->name }} </h3>
                    <textarea class="form-control" id="class_{{$class->id}}" rows="1" onblur='saveDescription({{$class->id}}, "class", this.value, this.id);'>{{ $class->description }}</textarea>
                    @if(count($class->methods) > 0)
                        <table class="table table-bordered table-striped table-hover">
                            <th width="12%">Function</th>
                            @if($class_type->name == "Controllers")
                                <th width="20%">URL</th>
                            @endif
                            <th>Description</th>
                            @foreach ($class->methods as $method)
                                <tr>
                                    <td> {{ $method->name }} </td>
                                    @if($class_type->name == "Controllers")
                                        <td> {{ $method->url }} </td>
                                    @endif
                                    <td>
                                        <input class="form-control" type="text" id="method_{{$method->id}}" value="{{ $method->description }}" onblur='saveDescription({{$method->id}}, "method", this.value, this.id);'/>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @endif
                @endforeach
            @endforeach
        @endforeach
    </div>

@endsection


@section('scripts')

    <script type="text/javascript">
        var _token = "{{ csrf_token() }}";
        // ajax call to save method or class description
        function saveDescription(id, type, description, element_id) {
            $("#"+element_id).css("background-color", "#ffff85");
            $.post('{{ route('ldocs-ajax') }}', {
                    _token,
                    id,
                    type: type,
                    description: description
                })
                .done(function (response) {
                    $("#"+element_id).css("background-color", "#d3ffcc");

                    console.log(response);
                })
                .fail(function (response) {
                    $("#"+element_id).css("background-color", "#ffac99");
                });          
        }
    </script>

@endsection
