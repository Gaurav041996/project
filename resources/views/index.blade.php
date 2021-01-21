<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <form enctype="multipart/form-data" id="prod_form">
                        <h3>Product Details</h3>
                        <div class="form-group">
                            <label>Product Name:</label>
                            <input type="text" name="product_name" id="product_name">
                        </div>
                        <div class="form-group">
                            <label>Product Price:</label>
                            <input type="text" name="product_price" id="product_price">
                        </div>
                        <div class="form-group">
                            <label>Stock:</label>
                            <input type="text" name="product_stock" id="product_stock">
                        </div>
                        <div class="form-group">
                            <label>Product Description:</label>
                            <textarea name="product_desc" id="product_desc"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Product Images:</label>
                            <input type="file" name="images[]" id="images" multiple>
                        </div>
                        <div>
                            <input type="submit" id="add" name="add" value="Add"  class="btn btn-primary"/>
                            <input type="button" id="update" name="update" value="Update"   class="btn btn-primary"/>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Prod Id</th>
                                <th>Product Name</th>
                                <th>Product Price</th>
                                <th>Stock</th>
                                <th>Description</th>
                                <th>Images</th>
                                <th>Actions</th>
                            </tr>
                            <tbody>

                            </tbody>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script>
        function load_table(){
            var path='{{url('/product')}}/';
            $.ajax({
                type: 'GET',
                url: '{{ url('/get_data') }}',
                success: function (data) {
                    if (data.status) {
                        var response = data.data;
                        var table_data='';
                        // console.log(response);
                        for(var i=0;i<response.length;i++){
                            var obj=JSON.parse(response[i]['images']);
                            // console.log(obj.length);
                            table_data+='<tr>'+
                                '<td>'+response[i]['id']+'</td>'+
                                '<td>'+response[i]['prodcut_name']+'</td>'+
                                '<td>'+response[i]['product_price']+'</td>'+
                                '<td>'+response[i]['stock']+'</td>'+
                                '<td>'+response[i]['product_description']+'</td><td>';
                                for(var x=0;x<obj.length;x++){
                                    // console.log(path+obj);
                                    table_data+= '<img src='+path+obj[x]+' height="20px" width="20px"/>';
                                }


                                table_data+= '</td><td><input type="button" id="deletebtn" value="Delete" class="btn btn-primary" onclick="deletep('+response[i]['id']+')"> </td>'+
                                '<td><input type="button" id="editbtn" value="Edit" class="btn btn-primary" onclick="edit('+response[i]['id']+')"> </td>'+
                                '<tr>';
                        }
                        $("tbody").empty().append(table_data);
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        }

        $(document).ready(function () {


            load_table();

            $("#prod_form").submit(function (e) {
               e.preventDefault();
               formdata=new FormData(this);
               var name=$("#product_name").val();
               var price=$("#product_price").val();
               var stock=$("#product_stock").val();
               var desc=$("#product_desc").val();
                formdata.append('name',name);
                formdata.append('price',price);
                formdata.append('stock',stock);
                formdata.append('desc',desc);

                var ins=document.getElementById("images").files.length;
                for(var i=0;i<ins;i++){
                    formdata.append("pimages[]",document.getElementById('images').files[i]);
                }


                $.ajax({
                type: 'POST',
                url: '{{ url('/product_add') }}',
                dataType: "json",
                contentType: false,
                processData: false,
                headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                data:formdata,
                success: function (data) {
                if(data.status){

                        var msg=data.msg;
                        alert(msg);
                    $("#prod_form")[0].reset();
                        // console.log(msg);
                        load_table();
                }

                }, error: function (jqXHR, textStatus, errorThrown) {
                }
                });
            });

            $("#update").click(function (e) {
                var id=$(this).attr("data-id");
                // alert(id);
                if(typeof id !== "undefined"){
                    e.preventDefault();
                    formdata=new FormData();
                    var name=$("#product_name").val();
                    var price=$("#product_price").val();
                    var stock=$("#product_stock").val();
                    var desc=$("#product_desc").val();

                    formdata.append('name',name);
                    formdata.append('price',price);
                    formdata.append('stock',stock);
                    formdata.append('desc',desc);
                    formdata.append('id',id);

                    var ins=document.getElementById("images").files.length;
                    for(var i=0;i<ins;i++){
                        formdata.append("pimages[]",document.getElementById('images').files[i]);
                    }


                    $.ajax({
                        type: 'POST',
                        url: '{{ url('/product_update') }}',
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        },
                        data:formdata,
                        success: function (data) {
                            if(data.status){

                                var msg=data.msg;
                                alert(msg);
                                $("#prod_form")[0].reset();
                                // console.log(msg);
                                load_table();
                            }

                        }, error: function (jqXHR, textStatus, errorThrown) {
                        }
                    });
                }
                else {
                    alert('Please select the Product');
                }

                });




        });

        function deletep(id){
            $.ajax({
                type: 'POST',
                url: '{{ url('/deleteProduct') }}',
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                data: {
                    'id':id
                },
                success: function (data) {
                    if(data.status){
                        var response= data.msg;
                        alert(response);
                        load_table();
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        }
        function edit(id){
            $.ajax({
                type: 'POST',
                url: '{{ url('/editProduct') }}',
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                },
                data: {
                    'id':id
                },
                success: function (data) {
                    if(data.status){
                        var response= data.response;
                        for(var i=0;i<response.length;i++){
                            // alert(response[i]['id']);
                            $("#product_name").val(response[i]['prodcut_name']);
                            $("#product_price").val(response[i]['product_price']);
                            $("#product_stock").val(response[i]['stock']);
                            $("#product_desc").val(response[i]['product_description']);
                            $("#update").attr("data-id",response[i]['id']);
                        }
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        }



    </script>
</html>