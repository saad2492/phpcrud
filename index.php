<?php
include "class.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
 
    <title>Document</title>
</head>
<body>
    <div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 ">
            <?php 
            $obj = new crud;
            if(isset($_POST['save'])){
                $name = $_POST['name'];
                $email = $_POST['email'];
                $status = $_POST['status'];

                if($name == ""){
                    echo '<span class="alert alert-danger">Name field must be required</span>';
                   }elseif($email == ""){
                    echo '<span class="alert alert-danger">Email field must be required</span>';
                
                   }else{
                    
                    $msg = $obj->insert($name,$email,$status);
                    echo $msg;
                   }
            }

            if(isset($_GET['uid'])){
              $id = $_GET["uid"];
              if($obj->delete($id)){
                echo '<span class="alert alert-success">Data deleted</span>';
              }else{
                echo '<span class="alert alert-danger">Data not deleted</span>';
              }
            }
            if(isset($_GET['update'])){
              $id = $_GET["update"];
              if($obj->update($_GET,$id)){
                echo '<span class="alert alert-success">Data updated</span>';
              }else{
                echo '<span class="alert alert-danger">Data not updated</span>';
              }
            }

            if(isset($_GET["status1"])){
              $sid = $_GET['status1'];
              $obj->status2($sid);

            }
            if(isset($_GET["status2"])){
              $sid = $_GET['status2'];
              $obj->status1($sid);

            }
            
            ?>
            <form action="" method="POST" class="mt-5">
                <div class="form-group">
                <label for="" >Username</label>
                <input type="text" class="form-control" name="name" placeholder="Enter your name">
                </div>
                <div class="form-group">
                <label for="">Email</label>
                <input type="email" class="form-control" name="email" placeholder="Enter your email">
                </div>
                <div class="form-group">
                <label for="">Status</label>
                <select name="status" class="form-control">
                  <option value="1">------status------</option>
                    <option value="1">Active</option>
                    <option value="2">Inactive</option>
                </select>
                </div>
                <button type="submit" name="save" class="form-control btn btn-primary mt-2">Save</button>
            </form>
        </div>
    </div>
    </div>
        <div class="row mt-3">
            <div class="col-md-6 offset-md-3">
                <table class="table table-striped display" border="1" id="myTable">
                  <thead>
                    <tr>
                        <th>SL No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                        <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                        $result = $obj->show();
                        $sl = 1;
                         while($data = $result->fetch_assoc()){
                            echo ' <tr>
                            <td>'.$sl.'</td>
                            <td>'.$data['name'].'</td>
                            <td>'.$data['email'].'</td>
                            <td>
                            <button data-toggle="modal" data-target="#edit'.$data["id"].'" class="btn btn-info btn-sm">
                            <i class="fas fa-edit"></i></button>
                            <a href="#" data-toggle="modal" data-target="#delete'.$data["id"].'"class="btn btn-danger btn-sm"><i class="fas fa-trash "></i></a></td>
                            ' ;

                            if($data['status'] == 1){
                              echo '<td><form method="GET"><button class="btn btn-info" name="status1" value="'.$data["id"].'">Active</button></form></td></tr>';
                              
                            }else{
                              echo '<td><form method="GET"><button class="btn btn-warning" name="status2" value="'.$data["id"].'">Inactive</button></form></td></tr>';
                            }

                        $sl++;
                    ?>

                       <!-- Delete Modal -->
<div class="modal fade" id="delete<?php echo $data['id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirmation message</h5>
      </div>
      <div class="modal-body">
        Are you sure want to delete this user?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
       <form action="" method="GET">
        <button class="btn btn-danger" name="uid" value="<?php echo $data['id'];?>">Delete</button>
       </form>
      </div>
    </div>
  </div>
</div>
      <!-- UpdateModal -->
  <div class="modal fade" id="edit<?php echo $data['id'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update information</h5>
      </div>
      <form action="" method="GET" > 
        <div class="modal-body"> 
             
          <div class="form-group">
              <label for="" >Username</label>
              <input type="text" class="form-control" name="name" value="<?php echo $data['name'];?>" >
            </div>
            <div class="form-group">
              <label for="">Email</label>
              <input type="email" class="form-control" name="email" value="<?php echo $data['email'];?>">
            </div>
            <div class="form-group">
              <label for="">Status</label>
              <select name="status" class="form-control">

                  <?php
                      if($data["status"] == 1){
                          echo  '<option value="1">Active</option>';
                      }else{
                          echo '<option value="2">Inactive</option>';
                      }
                  ?>
                      <option value="1">Active</option>
                      <option value="2">Inactive</option>
                </select>
            </div>
            </div>
            <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
          <button class="btn btn-info" name="update" value="<?php echo $data['id'];?>">Update</button>
         
        </div>
    </form>
    </div>
  </div>
</div>
<?php
                 }
                    ?>
                  </tbody>
                </table>


            </div>
        </div>
       
        
      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T"
        crossorigin="anonymous"></script>
        <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
       <script>
      
      $(document).ready( function () {
        $('#myTable').DataTable();
        } );
    </script>
</body>
</html>