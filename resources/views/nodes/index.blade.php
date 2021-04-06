@extends('nodes.layout')
 
@section('content')
   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
   

    <div id="tree">
        </div>
    <script>
        
    window.onload = function () {
    OrgChart.templates.ana.plus = '<circle cx="15" cy="15" r="15" fill="#ffffff" stroke="#aeaeae" stroke-width="1"></circle>'
        + '<text text-anchor="middle" style="font-size: 18px;cursor:pointer;" fill="#757575" x="15" y="22">{collapsed-children-count}</text>';

    OrgChart.templates.invisibleGroup.padding = [20, 0, 0, 0];

        var chart = new OrgChart(document.getElementById("tree"), {
                    template: "ana",
            enableDragDrop: true,
            nodeMenu: {
                add: { text: "Add" },
                remove: { text: "Remove" }
                
            },
            nodeBinding: {
                field_0: "id" ,
                field_1: "name",
            }
            
        });
      
        chart.on('add', function (sender, node) {
            node.id = new Date().valueOf();
            node.pid = node.pid;
            node.name =  Math.random().toString(36).substring(7);
            node.title = Math.random().toString(36).substring(7);
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                url:"{{ route('nodes.store') }}",
                data: node,
                success:function(data){
                    sender.addNode(node); // node is adding
                }
            });
            return false;
        });

        chart.on('remove', function (sender, nodeId) {

            var url = "{{URL('nodes')}}";
            var dltUrl = url+"/"+nodeId;

            $.ajax({
                url: dltUrl,
                type: "DELETE",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}'
                },
                success: function(dataResult){
                    var ele = JSON.parse(dataResult);
                    if(dataResult.statusCode==200){
                        $ele.fadeOut().remove();
                    }
                }
            });

        });
   
        var app = @json($nodes);
        chart.load(app);
    };
    </script>
      
@endsection
