<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel = "stylesheet">
</head>
<body>
<div class="container">
    <div class="col-md-6 col-md-offset-6" style="margin-top:60px;">
        <h4>View Posts</h4>
        <hr>
        <div class="m-2 d-flex justify-content-between">
            <a href="homepage">Go back to homepage</a>
            <a class="px-3 btn btn-success" href="javascript:void(0)" id="createNewPost">Add Post</a>
        </div>
        <table class="table table-borderless data-table">
            <tbody></tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="postform" name="postform" class="form-horizontal">
                    <input type="hidden" name="post_id" id="post_id">
                    <div class="form-group">
                        Post Description:<br>
                        <textarea class="form-control" rows="4" cols="50" id="description" name="description"
                        placeholder="Write your post" value="" required></textarea>
                        <span class="text-danger">@error('description') {{$message}} @enderror</span>
                    </div>
                    <button type="submit" class="my-2 btn btn-success" id="savebtn" value="create">Post</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
</body>
<script type="text/javascript">
$(function(){
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        }
    });
    var table=$(".data-table").DataTable({
        serverSide:true,
        processing:true,
        ajax:"{{route('posts.index')}}",
        columns:[
            {data:'username', name:'username'},
            {data:'description', name:'description'},
            {data:'action', name:'action'},
        ],
    });
    $count = table.columns().header().length
    table.order([$count-1,'desc']).draw();
    
    $("#createNewPost").click(function(){
        $("#post_id").val('');
        $("#postform").trigger('reset');
        $("#modalHeading").html('Add Post');
        $("#ajaxModel").modal('show');
    });
    $("#savebtn").click(function(e){
        e.preventDefault();
        $(this).html('Post');
        $.ajax({
            data:$("#postform").serialize(),
            url:"{{route('posts.create')}}",
            type:"GET",
            dataType:'json',
            success:function(data){
                $("#postform").trigger('reset');
                $("#ajaxModel").modal('hide');
                table.draw();
            },
            error:function(data){
                console.log('Error:',data);
                $("#savebtn").html("Post");
            }
        });
    });
    $('body').on('click','.deletepost', function(){
        var post_id = $(this).data('id');
        confirm("Are you sure want to delete?");
        $.ajax({
            type:"GET",
            url:"{{route('posts.destroy','post_id')}}",
            success:function(data){
                table.draw();
            },
            error:function(data){
                console.log('Error:',data);
            }
        });
    });
});
</script>
</html>