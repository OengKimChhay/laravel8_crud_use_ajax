@extends('layouts.app')
@section('content')

<div class="container mt-5">
   <div class="row">
      <div class="col-md-9 offset-md-1">
         <div class="card">
            <div class="card-header">
               <!-- Button trigger modal -->
               <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Student</button>
            </div>
            <div class="card-body">
               <table class="table">
                  <thead>
                     <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Password</th>
                        <th scope="col">create</th>
                        <th scope="col">update</th>
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                  @foreach($data as $info)
                     <tr>
                        <td>{{$info->id}}</td>
                        <td>{{$info->name}}</td>
                        <td>{{$info->email}}</td>
                        <td>{{$info->password}}</td>
                        <td>{{$info->created_at->format('F j Y')}}</td>
                        <td>{{$info->updated_at->diffForHumans()}}</td>
                        <td>
                           <button data-id="{{$info->id}}" class="delete btn btn-outline-danger">Delete</button>
                           <button data-id="{{$info->id}}" class="update btn btn-outline-primary">Update</button>
                        </td>
                     </tr>
                  @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>


<!--add Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="error"></div>
         <form id="form">
            @csrf
            <div class="form-group">
               <label for="email">Name:</label>
               <input type="text" class="form-control" placeholder="Enter Nmae" id="name" autocomplete="name">
            </div>
            <div class="form-group">
               <label for="email">Email:</label>
               <input type="text" class="form-control" placeholder="Enter Email" id="email" autocomplete="email">
            </div>
            <div class="form-group">
               <label for="password">Password:</label>
               <input type="password" class="form-control" placeholder="Enter Password" id="password" autocomplete="password">
            </div>              
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary">Submit</button>
            </div>
         </form>
      </div>
    </div>
  </div>
</div>

<!--edit Modal -->
<div class="modal fade" id="exampleEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <form id="formUpdate">
            @csrf
            <input type="hidden" name="id" id="id" value="">
            <div class="form-group">
               <label for="email">Name:</label>
               <input type="text" class="form-control" placeholder="Enter Nmae" id="updatename" autocomplete="name">
            </div>
            <div class="form-group">
               <label for="email">Email:</label>
               <input type="text" class="form-control" placeholder="Enter Email" id="updateemail" autocomplete="email">
            </div>
            <div class="form-group">
               <label for="password">Password:</label>
               <input type="password" class="form-control" placeholder="Enter Password" id="updatepassword" autocomplete="password">
            </div>              
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary">Submit</button>
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script>
   $(document).ready(function(){
      //save data---------------------------
      $('#form').on('submit',function(e){
            e.prventDefault;
            $.ajaxSetup({
               headers:{
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
            });
            $.ajax({
               url: "{{route('store')}}", //or url: '/add-student'
               type: "POST",
               data: {
                  name :   $("#name").val(),
                  email:   $("#email").val(),
                  password:    $("#password").val(),
                  _token:   $('meta[name="csrf-token"]').attr('content')
               },
               dataType: "JSON",
               // data: $('#form').serialize(), //បើយើងប្រើបែបនេះមានន័យថា value in all input fields​ ត្រូវបានចាប់យក​ដោយមិនចាំបាច់ declare let name= $('#name').val();.... តែត្រូវ​តែប្រកាស់ name attr គ្រប់ fields ទាំងអស់ in form
               success:function(response){
                  alert("success add student"); //success is an object when data response
                  // window.location.href= "{{route('home')}}"; // redirect to home page mean route('home); without refresh page
                  $("#exampleModal").modal("hide");
                  $('#form')[0].reset();
               },
               error:function(error){
                  $.each(error.responseJSON.errors, function (i, error) {
                     alert(error[0]);
                  });
               }
            });
      });

      //delete data--------------------------------------------------------
      $(".delete").on("click",function(e){
         e.prventDefault;
         $.ajaxSetup({
            headers: {
                  'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
         });

         if(!confirm("Are u sure to delete?")){
            return exit();
         }
         var id = $(this).data("id");
         $.ajax({
            url: "delete/"+id, 
            type: "DELETE",
            data: {
               _token:  $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
               if(response){
                  alert(response.success); //success is an object when data response
                  location.reload();  //use this for delete without reload the page
               }else{
                  alert('Doest have any Response!!');
               }
            },
            error:function(error){
               console.log(error.responseText);
               alert(error.responseText);
            }
         });
      });

      //edit -----------------
      $(".update").on("click",function(e){
         e.prventDefault;
         $.ajaxSetup({
            headers: {
               'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
         });
         var id = $(this).data("id");
         $.ajax({
            url: '/student/'+id,
            type: "GET",
            success:function(res){
               if(res){
                  $("#id").val(res.id);
                  $("#updatename").val(res.name);
                  $("#updateemail").val(res.email);
                  $("#updatepassword").val(res.password);
                  $("#exampleEditModal").modal('toggle');
               }else{
                  alert('Doest have any Response!!');
               }
            },
            error:function(error){
               console.log(error.responseText);
               alert(error.responseText);
            }
         });
      });

      // save update ------------------------------------------------
      $('#formUpdate').on('submit',function(e){
            e.prventDefault;
            $.ajaxSetup({
               headers:{
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
            });
            $.ajax({
               url:  "update-student", 
               type: "PUT",
               data: {
                  id:   $("#id").val(),
                  name :   $("#updatename").val(),
                  email:   $("#updateemail").val(),
                  password:$("#updatepassword").val(),
                  _token:   $('meta[name="csrf-token"]').attr('content')
               },
               dataType: "JSON",
               // data: $('#form').serialize(), //បើយើងប្រើបែបនេះមានន័យថា value in all input fields​ ត្រូវបានចាប់យក​ដោយមិនចាំបាច់ declare let name= $('#name').val();.... តែត្រូវ​តែប្រកាស់ name attr គ្រប់ fields ទាំងអស់ in form 
               success:function(response){
                  if(response){
                     alert(response.update);
                     $("#exampleEditModal").modal("hide");
                     $('#formUpdate')[0].reset();
                  }else{
                     alert('Doest have any Response!!');
                  }
               },
               error:function(error){
                  console.log(error);
                  alert(error.responseTextss);
               }
            });
      });
   });
</script>
@endsection