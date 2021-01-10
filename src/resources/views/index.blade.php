
@extends('ldocs::layouts.app')

@section('content')

    <div class="container content">
        @if(count($class_types) == 0)
            <div class="row">
                <div class="col-12">Good evening! <a href="{{route('ldocs-scan-project')}}">Scan</a> your project to begin...</div>
            </div>
        @else
            @foreach($class_types as $class_type)

                <div class="row class_type-wrapper @if($class_type->active == 0) inactive @endif">
                    <div class="col-12">
                        <h1> {{ $class_type->name }}</h1>
                        @if($edit)
                            <input type="checkbox" id="active_class_type_{{$class_type->id}}" @if($class_type->active == 1) checked @endif onclick='toggleActive({{$class_type->id}}, "class_type", this.checked, this.id);' />
                            <label for="active_class_type_{{$class_type->id}}"></label>
                        @endif
                    </div>

                    <div class="col-12">
                        @if($edit)
                            @php $namespaces = $class_type->namespaces @endphp
                        @else 
                            @php $namespaces = $class_type->activeNamespaces @endphp
                        @endif
                        @foreach ( $namespaces as $namespace)

                            <div class="row namespace-wrapper @if($namespace->active == 0) inactive @endif">
                                
                                <div class="col-12">
                                    <h2> {{ $namespace->name }} </h2>
                                    @if($edit)
                                        <input type="checkbox" id="active_namespace_{{$namespace->id}}" @if($namespace->active == 1) checked @endif onclick='toggleActive({{$namespace->id}}, "namespace", this.checked, this.id);' />
                                        <label for="active_namespace_{{$namespace->id}}"></label>
                                    @endif
                                </div>
                                <div class="col-12">
                                    @if($edit)
                                        @php $namespace_classes = $namespace->classes @endphp
                                    @else 
                                        @php $namespace_classes = $namespace->activeClasses @endphp
                                    @endif                                    
                                    @foreach ($namespace_classes as $class)

                                        <div class="row class-wrapper @if($class->active == 0) inactive @endif">
                                            <div class="col-12">
                                                <h3> {{ $class->name }} </h3>
                                                @if($edit)
                                                    <input type="checkbox" id="active_class_{{$class->id}}" @if($class->active == 1) checked @endif onclick='toggleActive({{$class->id}}, "class", this.checked, this.id);' />
                                                    <label for="active_class_{{$class->id}}"></label>                        
                                                    <textarea class="form-control" id="class_{{$class->id}}" rows="1" onblur='saveDescription({{$class->id}}, "class", this.value, this.id);'>{{ $class->description }}</textarea>
                                                @else
                                                    {{ $class->description }}
                                                @endif
                                                @if($edit)
                                                    @php $class_methods = $class->methods @endphp
                                                @else 
                                                    @php $class_methods = $class->activeMethods @endphp
                                                @endif                                                  
                                                @if(count($class_methods) > 0)
                                                    <table class="table table-bordered table-striped">
                                                        <th width="12%">Function</th>
                                                        @if($class_type->name == "Controllers")
                                                            <th width="20%">URL</th>
                                                        @endif
                                                        <th>Description</th>
                                                        @if($edit)
                                                            <th></th>
                                                        @endif
                                                        @foreach ($class_methods as $method)
                                                            <tr @if($method->active == 0) class="inactive" @endif>
                                                                <td> {{ $method->name }} </td>
                                                                @if($class_type->name == "Controllers")
                                                                    <td> {{ $method->url }} </td>
                                                                @endif
                                                                <td>
                                                                    @if($edit)
                                                                        <input class="form-control" type="text" id="method_{{$method->id}}" value="{{ $method->description }}" onblur='saveDescription({{$method->id}}, "method", this.value, this.id);'/>
                                                                    @else
                                                                        {{ $method->description }}
                                                                    @endif
                                                                </td>
                                                                @if($edit)                                      
                                                                    <td width="5%">
                                                                        <input type="checkbox" id="active_method_{{$method->id}}" @if($method->active == 1) checked @endif onclick='toggleActive({{$method->id}}, "method", this.checked, this.id);' />
                                                                        <label for="active_method_{{$method->id}}"></label>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                @endif
                                            </div>
                                        </div>

                                    @endforeach
                                </div>
                            </div>

                        @endforeach
                    </div>
                </div>

            @endforeach
        @endif
    </div>

@endsection


@section('scripts')

    <script type="text/javascript">

        var _token = "{{ csrf_token() }}";

        // ajax call to save method or class description
        function saveDescription(id, type, description, element_id) {
            $("#"+element_id).css("background-color", "#ffff85");
            $.post('{{ route('ldocs-ajax-description') }}', {
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
                    alert("Error saving description. Please try again.")
                });          
        }

        // ajax call to toggle the state of any item
        function toggleActive(id, type, active, element_id) {
            // $("#"+element_id).parent().parent().css("background-color", "#ffff85");
            $.post('{{ route('ldocs-ajax-active') }}', {
                    _token,
                    id,
                    type: type,
                    active: active
                })
                .done(function (response) {
                    // $("#"+element_id).parent().parent().addClass("background-color", "transparent");
                    if(active) {
                        $("#"+element_id).parent().parent().removeClass("inactive");
                    }
                    else {
                        $("#"+element_id).parent().parent().addClass("inactive");
                    }

                    console.log(response);
                })
                .fail(function (response) {
                    $("#"+element_id).prop('checked', !active);
                    alert("Error saving state. Please try again.")
                });          
        }

    </script>

@endsection
